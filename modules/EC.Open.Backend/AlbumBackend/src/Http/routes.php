<?php

$router->group(['prefix' => 'admin/store/image'], function () use ($router) {

    $router->group(['prefix' => 'file'], function () use ($router) {
        $router->get('/', 'ImagesController@index')->name('admin.image.index');
        $router->post('delete', 'ImagesController@delete')->name('admin.image.delete');

        $router->get('edit_name/{id}', 'ImagesController@editName')->name('admin.image.edit_name');
        $router->get('editImageCategory/{id}', 'ImagesController@editImageCategory')->name('admin.image.editImageCategory');
        $router->post('store', 'ImagesController@store')->name('admin.image.store');

        $router->get('editImageCategoryBatch', 'ImagesController@editImageCategoryBatch')->name('admin.image.editImageCategoryBatch');
        $router->post('saveBatch', 'ImagesController@saveBatch')->name('admin.image.saveBatch');

        $router->get('upload', 'ImagesController@upload')->name('admin.image.upload');
        $router->post('postUpload', 'UploadController@postUpload')->name('admin.image.postUpload');

        /*api*/
        $router->get('modal/get_category', 'ImagesController@getImageCategoryModal')->name('admin.image.modal.getImageCategoryModal');
        $router->get('modal/get_image', 'ImagesController@getImageDataModal')->name('admin.image.modal.getImageDataModal');
    });
    
    
    

    $router->group(['prefix' => 'category'], function () use ($router) {
        $router->get('/', 'ImageCategoryController@index')->name('admin.image-category.index');
        $router->get('create', 'ImageCategoryController@create')->name('admin.image-category.create');
        $router->get('edit/{id}', 'ImageCategoryController@edit')->name('admin.image-category.edit');
        
        $router->post('sort', 'ImageCategoryController@category_sort')->name('admin.image-category.category_sort');
        $router->post('store', 'ImageCategoryController@store')->name('admin.image-category.store');
        $router->post('delete', 'ImageCategoryController@delete')->name('admin.image-category.delete');
    });




//    $router->post('login', 'LoginController@login')->name('admin.login');

});
