<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\GalleryImage;
use CodeIgniter\API\ResponseTrait;

class GalleryRepository extends BaseController
{
    use ResponseTrait;

    private $GalleryImage;

    public function __construct()
    {
        $this->GalleryImage = new GalleryImage();
    }

    public function validationRules($data)
    {
        return [
            'GI_IMAGE' => [
                'label' => 'image',
                'rules' => ['uploaded[GI_IMAGE]', 'mime_in[GI_IMAGE,image/png,image/jpg,image/jpeg]', 'max_size[GI_IMAGE,2048]']
            ],
        ];
    }

    public function createUpdateGalleryImage($user, $data)
    {
        $user_id = $user['USR_ID'];
        if (empty($data['GI_ID']))
            unset($data['GI_ID']);

        if (!empty($image = $data['GI_IMAGE'])) {
            $image_name = $image->getName();
            $directory = "assets/Uploads/gallery_images/";
            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return responseJson(500, true, ['msg' => "Gallery image not uploaded"]);

            $data['GI_IMAGE'] = $directory . $response['RESPONSE']['OUTPUT'];
        } else
            unset($data['GI_IMAGE']);

        if (!empty($data['GI_ID']))
            $data['GI_UPDATED_BY'] = $user_id;
        else
            $data['GI_UPDATED_BY'] = $data['GI_CREATED_BY'] = $user_id;

        $response = $this->GalleryImage->save($data);

        return $response
            ? responseJson(200, false, ['msg' => 'Gallery image created/updated successfully.'], $response = '')
            : responseJson(500, true, ['msg' => 'db insert/update not successfull.']);
    }

    public function galleryImageById($id)
    {
        return $this->GalleryImage->find($id);
    }

    public function deleteGalleryImage($id)
    {
        return $this->GalleryImage->delete($id);
    }

    public function allImages()
    {
        return $this->GalleryImage->orderBy('GI_SEQUENCE', 'asc')->findAll();
    }
}
