<?php

namespace Packages\HeaderMenu;

use Illuminate\Support\Facades\DB;
use Packages\HeaderMenu\Models\HeaderMenu as Menu;

class HeaderMenu
{
    /**
     * update menus
     */
    public function reorder($menus, $parentId = null)
    {
        try {
            DB::beginTransaction();

            foreach ($menus as $index => $menu) {
                $id = data_get($menu, 'uuid');
                $menuModel = Menu::find($id);

                $menuModel->update([
                    'ranking' => $index,
                    'parent_id' => $parentId,
                ]);

                if ($subMenus = data_get($menu, 'subMenu')) {
                    $this->reorder($subMenus, $id);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function getFirstLevel()
    {
        return Menu::where('parent_id', null)->orderBy('ranking')->get();
    }
}
