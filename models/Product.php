<?php

namespace app\models;

use app\Database;
use app\helpers\UtilHelper;

class Product
{
    public $id = null;
    public $title = null;
    public $description = null;
    public $price = null;
    public $imagepath = null;
    public $imageFile = null;

    public function load($data)
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'];
        $this->description = $data['description'] ?? '';
        $this->price = (float) $data['price'];
        $this->imageFile = $data['imageFile'] ?? null;
        $this->image = $data['image'] ?? null;
    }

    public function save()
    {
        $errors = [];
        if (!is_dir(__DIR__ . '/../public/images')) {
            mkdir(__DIR__ . '/../public/images');
        }

        if (!$this->title) {
            $errors[] = 'Product title is required';
        }

        if (!$this->price) {
            $errors[] = 'Product price is required';
        }

        if (empty($errors)) {
            if ($this->imageFile && $this->imageFile['tmp_name']) {
                if ($this->imagePath) {
                    unlink(__DIR__ . '/../public/' . $this->imagePath);
                }
                $this->imagePath = 'images/' . UtilHelper::randomString(8) .
                '/' . $this->imageFile['name'];
                mkdir(dirname(__DIR__ . '/../public/' . $this->imagePath));
                move_uploaded_file($this->imageFile['tmp_name'], __DIR__ .
                    '/../public/' . $this->imagePath);
            }

            $db = new Database();
            if ($this->id) {
                $db->updateProduct($this);
            } else {
                $db->createProduct($this);
            }

        }
    }
}