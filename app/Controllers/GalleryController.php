<?php

namespace App\Controllers;

use App\Controllers\Repositories\GalleryRepository;
use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;

class GalleryController extends BaseController
{

    use ResponseTrait;

    private $GalleryRepository;

    public function __construct()
    {
        $this->GalleryRepository = new GalleryRepository();
    }

    public function gallery()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/gallery', $data);
    }

    public function allImages()
    {
        if (isWeb()) {
            $mine = new ServerSideDataTable();
            $tableName = 'FLXY_GALLERY_IMAGES';
            $columns = 'GI_ID,GI_IMAGE,GI_SEQUENCE,GI_CREATED_AT';
            $mine->generate_DatatTable($tableName, $columns);
            exit;
        } else {
            $gallery_images = $this->GalleryRepository->allImages();
            return $this->respond(responseJson(200, false, ['msg' => 'Gallery Images'], $gallery_images));
        }
    }

    public function store()
    {
        $user = session('user');
        $data = $this->request->getPost();
        $data['GI_IMAGE'] = $this->request->getFile('GI_IMAGE') ?? null;

        if ((empty($data['GI_ID']) || !empty($data['GI_IMAGE'])) && !$this->validate($this->GalleryRepository->validationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->GalleryRepository->createUpdateGalleryImage($user, $data);
        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $image = $this->GalleryRepository->galleryImageById($id);
        if ($image)
            return $this->respond(responseJson(200, false, ['msg' => "Gallery Image"], $image));

        return $this->respond(responseJson(404, true, ['msg' => "Gallery Image not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $result = $this->GalleryRepository->deleteGalleryImage($id);
        $result = $result
            ? responseJson(200, false, ['msg' => 'Gallery Image deleted successfully'])
            : responseJson(500, true, "record not deleted");

        return $this->respond($result);
    }
}
