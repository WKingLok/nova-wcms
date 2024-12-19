<?php

declare(strict_types=1);

namespace Packages\Approval\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * @method static static CLONE()
 * @method static static INACTIVE()
 * @method static static SUBMIT()
 * @method static static PUBLISH()
 * @method static static APPROVAL()
 * @method static static ARCHIVE()
 */
final class ApprovalAction extends Enum
{
    #[Description('Update')]
    const UPDATE = 'update';

    #[Description('Clone')]
    const CLONE = 'clone';

    #[Description('Submit')]
    const SUBMIT = 'submit';

    #[Description('Publish')]
    const PUBLISH = 'publish';

    #[Description('Approval')]
    const APPROVAL = 'approval';

    #[Description('Reject')]
    const REJECT = 'reject';

    #[Description('Archive')]
    const ARCHIVE = 'archive';
}
