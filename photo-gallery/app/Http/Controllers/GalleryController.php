<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Services\ImageServiceToS3;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    protected $imageService;

    public function __construct(ImageServiceToS3 $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $images = Image::all();
        return view('index', ['images' => $images]);
    }

    public function upload(Request $request)
    {
        $validate = validator(
            $request->all(),
            [
                'image' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif', Rule::dimensions()->maxWidth(1000)->maxHeight(1000)],
                'title' => ['required', 'string', 'max:255', 'min:6'],

            ]
        );

        if ($validate->fails()) {
            return redirect()->route('index')->withErrors($validate);
        }


        $title = $request->only('title');
        $image = $request->file('image');

        try {
            $this->imageService->storeNewImage($image, $title['title']);
        } catch (\Exception $e) {
            $this->imageService->rollback();
        }

        return redirect()->route('index')->with('success', 'Imagem enviada com sucesso.');
    }

    public function delete($id)
    {
        $image = Image::findOrFail($id);
        $url = parse_url($image->url);
        Storage::disk('s3')->delete($url);
        $image->delete();

        return redirect()->route('index')->with('success', 'Image deleted successfully.');
    }
}
