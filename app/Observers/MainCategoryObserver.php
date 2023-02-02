<?php

namespace App\Observers;

use App\Models\mainCategory as MainCategory;

class MainCategoryObserver
{
    /**
     * Handle the main category "created" event.
     *
     * @param  \App\mainCategory  $mainCategory
     * @return void
     */
    public function created(MainCategory $mainCategory)
    {
        //
    }

    /**
     * Handle the main category "updated" event.
     *
     * @param  \App\mainCategory  $mainCategory
     * @return void
     */
    public function updated(mainCategory $mainCategory)
    {
        $mainCategory->vendors()->update(['active' => $mainCategory->active]);
    }

    /**
     * Handle the main category "deleted" event.
     *
     * @param  \App\mainCategory  $mainCategory
     * @return void
     */
    public function deleted(mainCategory $mainCategory)
    {
        //
    }

    /**
     * Handle the main category "restored" event.
     *
     * @param  \App\mainCategory  $mainCategory
     * @return void
     */
    public function restored(mainCategory $mainCategory)
    {
        //
    }

    /**
     * Handle the main category "force deleted" event.
     *
     * @param  \App\mainCategory  $mainCategory
     * @return void
     */
    public function forceDeleted(mainCategory $mainCategory)
    {
        //
    }
}
