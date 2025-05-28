<?php

use App\Modules\Group\Controllers\GroupController;
use Illuminate\Support\Facades\Route;
use App\Modules\Group\Controllers\GroupMemberController;


// Định nghĩa các route ở đây
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
    
    // Phần nhóm
    Route::resource('group', GroupController::class);
    
});

    Route::prefix('admin/groupmembers')->group(function () {
        Route::get('/{groupId}', [GroupMemberController::class, 'index'])->name('admin.groupmember.index'); // Danh sách thành viên nhóm
        Route::get('/{groupId}/create', [GroupMemberController::class, 'create'])->name('admin.groupmember.create'); // Tạo thành viên nhóm
        Route::post('/{groupId}', [GroupMemberController::class, 'store'])->name('admin.groupmember.store'); // Lưu thành viên nhóm mới
        // Route::get('/edit/{id}', [GroupMemberController::class, 'edit'])->name('admin.groupmember.edit'); // Chỉnh sửa thành viên
        Route::get('/{groupId}/edit/{id}', [GroupMemberController::class, 'edit'])->name('admin.groupmember.edit');
        Route::patch('/{groupId}/{id}', [GroupMemberController::class, 'update'])->name('admin.groupmember.update'); // Cập nhật thành viên
        Route::delete('/{id}', [GroupMemberController::class, 'destroy'])->name('admin.groupmember.destroy'); // Xóa thành viên
    });


 
// Define routes here

use App\Modules\Group\Controllers\GroupTypeController;
use App\Modules\Group\Controllers\GroupRoleController;


use App\Modules\Group\Controllers\GroupFolderController;
use App\Modules\Group\Controllers\FrontGroupController;


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('group', GroupController::class);
    Route::post('group_status', [GroupController::class, 'groupStatus'])->name('group.status');
    Route::get('group_search', [GroupController::class, 'groupSearch'])->name('group.search');
    Route::get('group_member/{slug}', [GroupMemberController::class, 'groupMemberList'])->name('group.members');
    Route::get('group_addmember/{slug}', [GroupMemberController::class, 'groupAddMember'])->name('groupmember.addmember');

    Route::prefix('groupmembers')->group(function () {
        Route::get('/{groupId}', [GroupMemberController::class, 'index'])->name('groupmember.index');
        Route::get('/{groupId}/create', [GroupMemberController::class, 'create'])->name('groupmember.create');
        Route::post('/{groupId}', [GroupMemberController::class, 'store'])->name('groupmember.store');
        Route::get('/{groupId}/edit/{id}', [GroupMemberController::class, 'edit'])->name('groupmember.edit');
        Route::patch('/{groupId}/{id}', [GroupMemberController::class, 'update'])->name('groupmember.update');
        Route::delete('/{id}', [GroupMemberController::class, 'destroy'])->name('groupmember.destroy');
    });

    Route::resource('grouptype', GroupTypeController::class);
    Route::post('grouptype_status', [GroupTypeController::class, 'grouptypeStatus'])->name('grouptype.status');
    Route::get('grouptype_search', [GroupTypeController::class, 'grouptypeSearch'])->name('grouptype.search');

    Route::resource('grouprole', GroupRoleController::class);
    Route::post('grouprole_status', [GroupRoleController::class, 'grouproleStatus'])->name('grouprole.status');
    Route::get('grouprole_search', [GroupRoleController::class, 'grouproleSearch'])->name('grouprole.search');
});

Route::group(['prefix' => 'front', 'as' => 'front.'], function () {
    Route::get('groups', [FrontGroupController::class, 'frontGroupShow'])->name('groups.show');
});
