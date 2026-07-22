<?php

namespace App\Http\Controllers;

use App\Models\MenuSection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $sections = $this->menuSections();

        return view('menu', compact('sections'));
    }

    public function pdfView()
    {
        $sections = $this->menuSections();

        $sections = array_map(function (array $section): array {
            $section['items'] = array_map(function (array $item): array {
                $item['pdf_image'] = $this->optimizePdfImage($item['image'] ?? null);

                return $item;
            }, $section['items'] ?? []);

            return $section;
        }, $sections);

        return view('menu-pdf', compact('sections'));
    }

    private function menuSections(): array
    {
        $sections = MenuSection::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['meals' => function ($query) {
                $query->where('is_active', true)
                    ->where('is_hidden', false)
                    ->orderBy('sort_order')
                    ->orderBy('id');
            }])
            ->get()
            ->map(function (MenuSection $section) {
                $fallbackNumber = 1;

                return [
                    'title' => $section->title,
                    'subtitle' => $section->subtitle,
                    'items' => $section->meals->map(function ($meal) use (&$fallbackNumber) {
                        $fallback = $fallbackNumber;
                        $fallbackNumber++;

                        $price = (float) $meal->price;
                        $decimals = abs($price - floor($price)) < 0.00001 ? 0 : 2;

                        return [
                            'number' => $meal->sort_order > 0 ? (int) $meal->sort_order : $fallback,
                            'name' => $meal->name,
                            'price' => '₦'.number_format($price, $decimals),
                            'description' => $meal->description,
                            'image' => $meal->image,
                            'tags' => $meal->tags ?? [],
                        ];
                    })->toArray(),
                ];
            })
            ->toArray();

        // Remove sections that have no visible meals
        $sections = array_values(array_filter($sections, function ($section) {
            return !empty($section['items']);
        }));

        return $sections;
    }

    private function optimizePdfImage(?string $image): ?string
    {
        if (empty($image)) {
            return null;
        }

        $sourcePath = $this->resolveImageSourcePath($image);

        if (!$sourcePath || !is_file($sourcePath)) {
            return $this->resolveImageUrl($image);
        }

        $cacheDirectory = public_path('pdf-cache');
        if (!is_dir($cacheDirectory)) {
            mkdir($cacheDirectory, 0775, true);
        }

        $cacheName = sha1($sourcePath.'|1200|90').'.jpg';
        $cachePath = $cacheDirectory.DIRECTORY_SEPARATOR.$cacheName;

        if (!is_file($cachePath)) {
            $this->createOptimizedPdfImage($sourcePath, $cachePath, 1200, 90);
        }

        return asset('pdf-cache/'.$cacheName);
    }

    private function resolveImageSourcePath(string $image): ?string
    {
        if (Str::startsWith($image, ['http://', 'https://'])) {
            return null;
        }

        if (Str::startsWith($image, '/')) {
            $path = public_path(ltrim($image, '/'));

            return is_file($path) ? $path : null;
        }

        if (Str::startsWith($image, ['assets/', 'images/'])) {
            $path = public_path($image);

            return is_file($path) ? $path : null;
        }

        $storagePath = Storage::disk('public')->path($image);

        return is_file($storagePath) ? $storagePath : null;
    }

    private function resolveImageUrl(string $image): string
    {
        if (Str::startsWith($image, ['http://', 'https://', '/'])) {
            return $image;
        }

        if (Str::startsWith($image, ['assets/', 'images/', 'storage/'])) {
            return asset($image);
        }

        return Storage::url($image);
    }

    private function createOptimizedPdfImage(string $sourcePath, string $targetPath, int $maxWidth, int $quality): void
    {
        $imageInfo = getimagesize($sourcePath);

        if ($imageInfo === false) {
            copy($sourcePath, $targetPath);

            return;
        }

        [$width, $height, $type] = $imageInfo;

        if ($width <= $maxWidth && in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_WEBP], true)) {
            copy($sourcePath, $targetPath);

            return;
        }

        $sourceImage = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($sourcePath),
            IMAGETYPE_GIF => imagecreatefromgif($sourcePath),
            default => null,
        };

        if (!$sourceImage) {
            copy($sourcePath, $targetPath);

            return;
        }

        $targetWidth = min($maxWidth, $width);
        $targetHeight = (int) round(($targetWidth / $width) * $height);

        $destinationImage = imagecreatetruecolor($targetWidth, $targetHeight);
        $backgroundColor = imagecolorallocate($destinationImage, 255, 255, 255);
        imagefill($destinationImage, 0, 0, $backgroundColor);
        imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        imagejpeg($destinationImage, $targetPath, $quality);

        imagedestroy($sourceImage);
        imagedestroy($destinationImage);
    }
}
