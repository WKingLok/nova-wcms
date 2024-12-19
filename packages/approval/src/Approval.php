<?php

namespace Packages\Approval;

use Packages\Approval\Enums\ApprovalAction;
use Packages\Approval\Enums\ApprovalStatus;
use Packages\Approval\Models\Approval as ModelsApproval;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class Approval
{
    protected $model;
    protected $administrator;

    public static $statusNovaBadgeMapping = [
        null => 'info',
        'archive' => 'warning',
        'draft' => 'danger',
        'rejected' => 'danger',
        'published' => 'success',
        'pending approval' => 'info',
        'pending publish' => 'info',
    ];

    public static $actionNovaBadgeMapping = [
        '-' => 'info',
        'publish' => 'success',
        'update' => 'warning',
    ];

    public function init($model, $administrator = null)
    {
        $this->model = $model;
        $this->administrator = $administrator;
        return $this;
    }

    public function isEditorRole(): bool
    {
        if (!$this->administrator) {
            return false;
        }
        return $this->administrator->hasRole(config('approval.editor_roles'));
    }

    public function isApproverRole(): bool
    {
        if (!$this->administrator) {
            return false;
        }
        return $this->administrator->hasRole(config('approval.approver_roles'));
    }

    public function getActions()
    {
        $actions = [];

        /**
         * Role = Editor
         * Status = NULL / DRAFT
         */
        if ((!$this->model->approval || $this->model->approval->status == ApprovalStatus::DRAFT()) && $this->isEditorRole()) {
            array_push($actions,   [
                'action' => ApprovalAction::SUBMIT,
                'label' => __('Submit for approval'),
            ]);
        }

        /**
         * Role = Editor
         * Status = APPROVED
         * Action = SUBMIT 
         */
        if (
            $this->model->approval->status
            && $this->model->approval->status == ApprovalStatus::APPROVED
            && $this->model->approval->action == ApprovalAction::SUBMIT
            && $this->isEditorRole()
        ) {
            $actions = array_merge($actions, [
                [
                    'action' => ApprovalAction::PUBLISH,
                    'label' => __('Approval for Publishing'),
                ]
            ]);
        }

        /**
         * Role = Editor
         * Status = APPROVED
         * Action = PUBLISH
         */
        if (
            $this->model->approval->status
            && $this->model->approval->status == ApprovalStatus::APPROVED
            && $this->model->approval->action == ApprovalAction::PUBLISH
            && $this->isEditorRole()
        ) {
            if (!ModelsApproval::where('group_id', $this->model->approval->group_id)
                ->where('status', ApprovalStatus::DRAFT)->first()) {
                array_push($actions,   [
                    'action' => ApprovalAction::CLONE,
                    'label' => __('Clone'),
                ]);
            }
        }

        /**
         * Role = Approver
         * Status = PENDING
         * Action = *
         */
        if (
            $this->model->approval->status &&
            $this->model->approval->status == ApprovalStatus::PENDING &&
            $this->isApproverRole()
        ) {
            $actions = array_merge($actions, [
                [
                    'action' => ApprovalAction::REJECT,
                    'label' => __('Reject'),
                ],
                [
                    'action' => $this->model->approval->action,
                    'label' => __('Approval'),
                ],
            ]);
        }

        /**
         * Role = Editor
         * Status = REJECTED
         * Action = SUBMIT
         */
        if (
            $this->model->approval->status
            && $this->model->approval->status == ApprovalStatus::REJECTED
            && $this->model->approval->action == ApprovalAction::SUBMIT
            && $this->isEditorRole()
        ) {
            array_push($actions,   [
                'action' => ApprovalAction::SUBMIT,
                'label' => __('Submit for approval'),
            ]);
        }

        /**
         * Role = Editor
         * Status = REJECTED
         * Action = PUBLISH
         */
        if (
            $this->model->approval->status
            && $this->model->approval->status == ApprovalStatus::REJECTED
            && $this->model->approval->action == ApprovalAction::PUBLISH
            && $this->isEditorRole()
        ) {
            array_push($actions,   [
                'action' => ApprovalAction::PUBLISH,
                'label' => __('Submit for approval'),
            ]);
        }

        /**
         * Role = Editor
         * Status = REJECTED
         */
        if (
            $this->model->approval->status
            && $this->model->approval->status == ApprovalStatus::ARCHIVE
            && $this->isEditorRole()
        ) {
            if (!ModelsApproval::where('group_id', $this->model->approval->group_id)
                ->where('status', ApprovalStatus::DRAFT)->first()) {
                array_push($actions,   [
                    'action' => ApprovalAction::CLONE,
                    'label' => __('Clone'),
                ]);
            }
        }

        return $actions;
    }

    public function getStatus()
    {
        return data_get($this->model, 'approval.status', ApprovalStatus::DRAFT());
    }

    public function getAction()
    {
        return data_get($this->model, 'approval.action');
    }

    public function getHistory()
    {
        return data_get($this->model, 'approval.history', []);
    }

    public function handleAction($action)
    {
        $model = $this->model;

        /**
         * Role = Editor
         * Action = UPDATE
         */
        if ($action == ApprovalAction::UPDATE &&  $this->isEditorRole()) {
            $approval = $model->approval;

            if (!$approval) {
                ModelsApproval::create([
                    'group_id' => data_get($approval, 'group_id', Str::uuid()),
                    'approvable_id' => $model->id,
                    'approvable_type' => get_class($model),
                    'status' => ApprovalStatus::DRAFT,
                    'action' => null,
                    'version' => data_get($approval, 'version', 0) + 1,
                    'history' => [
                        [
                            'administrator' => Arr::only($this->administrator->toArray(), ['id', 'name', 'email']),
                            'action' => ApprovalAction::UPDATE,
                            'datetime' => Carbon::now()

                        ]
                    ]
                ]);
                return;
            }

            $approval->update([
                'status' => ApprovalStatus::DRAFT,
                'action' => null,
                'history' => [
                    ...$approval->history,
                    [
                        'administrator' => Arr::only($this->administrator->toArray(), ['id', 'name', 'email']),
                        'action' => ApprovalAction::UPDATE,
                        'datetime' => Carbon::now()

                    ]
                ]
            ]);
        }

        /**
         * Role = Editor
         * Action = Submit
         */
        if ($action == ApprovalAction::SUBMIT && $this->isEditorRole()) {
            $approval = $model->approval;

            if (!$approval) {
                ModelsApproval::create([
                    'group_id' => data_get($approval, 'group_id', Str::uuid()),
                    'approvable_id' => $model->id,
                    'approvable_type' => get_class($model),
                    'status' => ApprovalStatus::PENDING,
                    'action' => ApprovalAction::SUBMIT,
                    'version' => data_get($approval, 'version', 0) + 1,
                    'history' => [
                        [
                            'administrator' => Arr::only($this->administrator->toArray(), ['id', 'name', 'email']),
                            'action' => ApprovalAction::SUBMIT,
                            'datetime' => Carbon::now()

                        ]
                    ]
                ]);
                return;
            }

            $approval->update([
                'status' => ApprovalStatus::PENDING,
                'action' => ApprovalAction::SUBMIT,
                'history' => [
                    ...$approval->history,
                    [
                        'administrator' => Arr::only($this->administrator->toArray(), ['id', 'name', 'email']),
                        'action' => ApprovalAction::SUBMIT,
                        'datetime' => Carbon::now()

                    ]
                ]
            ]);
        }

        /**
         * Role = Approver
         * Action = Submit
         */
        if ($action == ApprovalAction::SUBMIT && $this->isApproverRole()) {
            $approval = $model->approval;

            if (!$approval) {
                return;
            }

            $approval->update([
                'status' => ApprovalStatus::APPROVED,
                'history' => [
                    ...$approval->history,
                    [
                        'administrator' => Arr::only($this->administrator->toArray(), ['id', 'name', 'email']),
                        'action' => ApprovalAction::APPROVAL,
                        'datetime' => Carbon::now()
                    ]
                ]
            ]);
        }

        /**
         * Role = Approver
         * Action = Reject
         */
        if ($action == ApprovalAction::REJECT && $this->isApproverRole()) {
            $approval = $model->approval;

            if (!$approval) {
                return;
            }

            $approval->update([
                'status' => ApprovalStatus::REJECTED,
                'history' => [
                    ...$approval->history,
                    [
                        'administrator' => Arr::only($this->administrator->toArray(), ['id', 'name', 'email']),
                        'action' => ApprovalAction::REJECT,
                        'datetime' => Carbon::now()
                    ]
                ]
            ]);
        }

        /**
         * Role = Editor
         * Action = PUBLISH
         */
        if ($action == ApprovalAction::PUBLISH && $this->isEditorRole()) {
            $approval = $model->approval;

            if (!$approval) {
                return;
            }

            $approval->update([
                'status' => ApprovalStatus::PENDING,
                'action' => ApprovalAction::PUBLISH,
                'history' => [
                    ...$approval->history,
                    [
                        'administrator' => Arr::only($this->administrator->toArray(), ['id', 'name', 'email']),
                        'action' => ApprovalAction::PUBLISH,
                        'datetime' => Carbon::now()
                    ]
                ]
            ]);
        }

        /**
         * Role = Approver
         * Action = PUBLISH
         */
        if ($action == ApprovalAction::PUBLISH && $this->isApproverRole()) {
            $approval = $model->approval;

            if (!$approval) {
                return;
            }

            //archive other
            ModelsApproval::where('group_id', $approval->group_id)
                ->where('action', ApprovalAction::PUBLISH)
                ->where('status', ApprovalStatus::APPROVED)
                ->get()
                ->each
                ->update([
                    'status' => ApprovalStatus::ARCHIVE,
                    'action' => null,
                    'history' => [
                        ...$approval->history,
                        [
                            'administrator' => null,
                            'action' => ApprovalAction::ARCHIVE,
                            'datetime' => Carbon::now()
                        ]
                    ]
                ]);



            $approval->update([
                'status' => ApprovalStatus::APPROVED,
                'history' => [
                    ...$approval->history,
                    [
                        'administrator' => Arr::only($this->administrator->toArray(), ['id', 'name', 'email']),
                        'action' => ApprovalAction::PUBLISH,
                        'datetime' => Carbon::now()
                    ]
                ]
            ]);
        }

        /**
         * Role = Editor
         * Action = CLONE
         */
        if ($action == ApprovalAction::CLONE && $this->isEditorRole()) {
            $approval = $model->approval;

            if (!$approval) {
                return;
            }

            $newModel = $model->replicate();
            $newModel->save();
            ModelsApproval::create([
                'group_id' => data_get($approval, 'group_id', Str::uuid()),
                'approvable_id' => $newModel->id,
                'approvable_type' => get_class($newModel),
                'status' => ApprovalStatus::DRAFT,
                'action' => null,
                'version' => data_get($approval, 'version', 0) + 1,
                'history' => [
                    [
                        'administrator' => Arr::only($this->administrator->toArray(), ['id', 'name', 'email']),
                        'action' => ApprovalAction::CLONE,
                        'datetime' => Carbon::now()

                    ]
                ]
            ]);

            return [
                'action' => ApprovalAction::CLONE,
                'newModel' => $newModel,
            ];
        }
    }

    public function displayStatusLabel()
    {
        $status = $this->getStatus();
        $action = $this->getAction();

        if ($status == ApprovalStatus::APPROVED && $action == ApprovalAction::SUBMIT) {
            return 'pending publish';
        }

        if ($status == ApprovalStatus::APPROVED && $action == ApprovalAction::PUBLISH) {
            return 'published';
        }

        if ($status == ApprovalStatus::PENDING) {
            return 'pending approval';
        }

        if ($status == ApprovalStatus::REJECTED) {
            return 'rejected';
        }

        if ($status == ApprovalStatus::ARCHIVE) {
            return 'archive';
        }

        return 'draft';
    }

    public function displayActionLabel()
    {
        $status = $this->getStatus();
        $action = $this->getAction();

        if (!in_array($status, [ApprovalStatus::PENDING, ApprovalStatus::REJECTED])) {
            return '-';
        }

        if ($action == ApprovalAction::PUBLISH) {
            return 'publish';
        }

        if ($action == ApprovalAction::SUBMIT) {
            return 'update';
        }

        return '-';
    }
}
