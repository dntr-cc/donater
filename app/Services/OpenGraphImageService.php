<?php

namespace App\Services;

use App\Models\Fundraising;
use App\Models\User;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use function Emoji\remove_emoji;

class OpenGraphImageService
{
    protected Filesystem $filesystem;
    protected TemporaryDirectory $tmpDir;

    public function __construct()
    {
        $this->filesystem = Storage::disk('opengraph');
        $this->tmpDir = TemporaryDirectory::make();
    }

    public function getUserImage(User $user, bool $getOld = true): string
    {
        $fileName = $this->getOpenGraphUserImageName($user->getId());
        $image = url('/images/donater.com.ua.png');
        if ($getOld && $this->filesystem->exists($fileName)) {
            return $this->filesystem->url($fileName);
        }
        try {
            $image = $this->generateOpenGraphUserImage($user, $fileName, $getOld);
        } catch (\Throwable $throwable) {
            \Log::critical($throwable->getMessage(), ['tarce' => $throwable->getTraceAsString()]);
        }

        return $image;
    }

    public function getFundraisingImage(Fundraising $fundraising, bool $getOld = true): string
    {
        $fileName = $this->getOpenGraphImageFundraisingName($fundraising->getId());
        $image = url('/images/donater.com.ua.png');
        if ($getOld && $this->filesystem->exists($fileName)) {
            return $this->filesystem->url($fileName);
        }
        try {
            $image = $this->generateOpenGraphFundraisingImage($fundraising, $fileName, true);
        } catch (\Throwable $throwable) {
            \Log::critical($throwable->getMessage(), ['tarce' => $throwable->getTraceAsString()]);
        }

        return $image;
    }

    /**
     * @param int $userId
     * @return string
     */
    protected function getOpenGraphUserImageName(int $userId): string
    {
        return strtr('open-graph-donater-com-ua-user-id-:userId.png', [':userId' => $userId]);
    }

    /**
     * @param int $fundraisingId
     * @return string
     */
    protected function getOpenGraphImageFundraisingName(int $fundraisingId): string
    {
        return strtr('open-graph-donater-com-ua-fundraising-id-:fundraisingId.png', [':fundraisingId' => $fundraisingId]);
    }

    /**
     * @param User $user
     * @param string $openGraphImageName
     * @param bool $removeOld
     * @return string|null
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    private function generateOpenGraphUserImage(User $user, string $openGraphImageName, bool $removeOld = false): ?string
    {
        $manager = new ImageManager(new Driver());
        $donatTmpPath = $this->tmpDir->path('donut.png');
        $filepathScaledAvatar = $this->tmpDir->path($openGraphImageName);
        $imageTemplate = $manager->read(base_path('public/images/opengraph/template/ava-mask.png'));
        $imageAvatar = $manager->read(base_path('public/' . $user->getAvatar()));
        $scaledAvatar = $imageAvatar->scale(width: 380)->cover(380, 380)->toPng();
        $scaledAvatar->save($filepathScaledAvatar);
        $this->createMaskedAvatar($filepathScaledAvatar);
        $imageScaledAvatar = $manager->read($filepathScaledAvatar);
        $imageTemplate->place($imageScaledAvatar, 'top-left', 49, 20);
        $manager->read(base_path('public/images/opengraph/template/donut.png'))->toPng()->save($donatTmpPath);
        $imageTemplate->place($donatTmpPath, 'top-left', 270, 270);

        $offset = 20;
        if (!empty(trim($user->getFullName()))) {
            $imageUsernamePath = $this->getTextImagePath('Bold', static::removeEmoji($user->getFullName()), 45, $manager);
            $imageTemplate->place($imageUsernamePath, 'top-right', 50, $offset);
            $offset += 65;
        }
        $imageUsernamePath = $this->getTextImagePath('Regular', '@' . static::removeEmoji($user->getUsername()), 50, $manager);
        $imageTemplate->place($imageUsernamePath, 'top-right', 50, $offset);
        $offset += 65;

        $fundraisings = $user->getFundraisings();
        $sourcesLines = [
            [
                ['Підписок на волонтерів:', (string)$user->getSubscribersAsSubscriber()->count() . ' ос.'],
                ['Зроблено донатів:', $user->getDonateCount() . ' шт.'],
                ['Задоначено через сайт:', $user->getDonatesSumAll() . ' грн.'],
                ['Додано призів:', $user->getPrizesCount() . ' шт.'],
            ],
        ];
        if ($fundraisings->count()) {
            $rows = app(\App\Services\RowCollectionService::class)->getRowCollection($fundraisings);
            $sourcesLines[] = [
                ['Підписалося Донатерів:', (string)$user->getSubscribers()->count() . ' ос.'],
                ['Всього зборів:', $user->getFundraisings()->count() . ' шт.'],
                ['Загалом зібрано коштів:', $rows->allSum() . ' грн.'],
                ['Зібрано від Донатерів:', $rows->allSumFromOurDonates() . ' грн.'],
            ];
        }
        foreach ($sourcesLines as $source) {
            foreach ($source as [$left, $right]) {
                $imageLinePath = $this->getTextImagePath('Regular', $right, 25, $manager);
                $imageTemplate->place($imageLinePath, 'top-right', 50, $offset);
                $imageLinePath = $this->getTextImagePath('Regular', $left, 25, $manager);
                $imageTemplate->place($imageLinePath, 'top-left', 500, $offset);
                $offset += 30;
            }
            $offset += 35;
        }

        $encoded = $imageTemplate->toPng();
        if ($removeOld) {
            $this->filesystem->delete($openGraphImageName);
        }
        $encoded->save(base_path('public/images/opengraph/' . $openGraphImageName));

        return $this->filesystem->url($openGraphImageName);
    }

    /**
     * @param User $fundraising
     * @param string $openGraphImageName
     * @param bool $removeOld
     * @return string|null
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    private function generateOpenGraphFundraisingImage(Fundraising $fundraising, string $openGraphImageName, bool $removeOld = false): ?string
    {
        $manager = new ImageManager(new Driver());
        $filepathScaledAvatar = $this->tmpDir->path($openGraphImageName);
        $imageTemplate = $manager->read(base_path('public/images/opengraph/template/fund-mask.png'));
        $imageAvatar = $manager->read(base_path('public/' . $fundraising->getAvatar()));
        $width = $imageAvatar->width();
        $height = $imageAvatar->height();
        if ($width > $height) {
            $imageAvatar = $imageAvatar->scale(width: 400);
        } else {
            $imageAvatar = $imageAvatar->scale(height: 400);
        }
        $imageAvatar->toPng()->save($filepathScaledAvatar);
        $imageScaledAvatar = $manager->read($filepathScaledAvatar);
        $imageTemplate->place($imageScaledAvatar, 'top-left', 20, 20);

        $offset = 20;
        $texts = [];
        $text = static::removeEmoji($fundraising->getName());
        if (mb_strlen($text) > 25) {
            $x = 25;
            $texts = explode("\n", wordwrap($text, $x));
        } else {
            $texts[] = $text;
        }
        foreach ($texts as $text) {
            if (!trim($text)) {
                continue;
            }
            $imageUsernamePath = $this->getTextImagePath('Bold', $text, 60, $manager);
            $imageTemplate->place($imageUsernamePath, 'top-right', 50, $offset);
            $offset += 50;
        }

        $offset += 20;
        $imageUsernamePath = $this->getTextImagePath('Light', 'https://' . $fundraising->getShortLink(), 50, $manager);
        $imageTemplate->place($imageUsernamePath, 'top-right', 50, $offset);

        $volunteer = $fundraising->getVolunteer();
        $imageAvatar = $manager->read(base_path('public/' . $volunteer->getAvatar()));
        $scaledAvatar = $imageAvatar->scale(width: 380)->cover(380, 380)->toPng();
        $scaledAvatar->save($filepathScaledAvatar);
        $this->createMaskedAvatar($filepathScaledAvatar);
        $imageScaledAvatar = $manager->read($filepathScaledAvatar);
        $imageTemplate->place($imageScaledAvatar->scale(150, 150), 'top-left', 480, 280);
        $offsetY = 310;
        $imageUsernamePath = $this->getTextImagePath('Medium', 'Волонтер ' . static::removeEmoji($volunteer->getFullName()), 25, $manager);
        $imageTemplate->place($imageUsernamePath, 'top-left', 650, $offsetY);
        $offsetY += 35;
        $imageUsernamePath = $this->getTextImagePath('Medium', 'чекає на вашу підписку, від 1грн в день.', 25, $manager);
        $imageTemplate->place($imageUsernamePath, 'top-left', 650, $offsetY);
        $offsetY += 35;
        $imageUsernamePath = $this->getTextImagePath('Medium', 'Посиланням на банку приходить в телеграм.', 25, $manager);
        $imageTemplate->place($imageUsernamePath, 'top-left', 650, $offsetY);

        $encoded = $imageTemplate->toPng();
        if ($removeOld) {
            $this->filesystem->delete($openGraphImageName);
        }
        $encoded->save(base_path('public/images/opengraph/' . $openGraphImageName));

        return $this->filesystem->url($openGraphImageName);
    }

    /**
     * @param string $fontType
     * @param string $text
     * @param int $fontSize
     * @return string
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    protected function getTextImageSavedPath(string $fontType, string $text, int $fontSize): string
    {
        $imageText = new Imagick();
        $draw = new ImagickDraw();
        $color = new ImagickPixel('#000000');
        $background = new ImagickPixel('none');
        $draw->setFont(base_path('public/fonts/Iskra/IskraCYR-' . $fontType . '.otf'));
        $draw->setFontSize($fontSize);
        $draw->setFillColor($color);
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $metrics = $imageText->queryFontMetrics($draw, $text);
        $draw->annotation(0, $metrics['ascender'], $text);
        $imageText->newImage($metrics['textWidth'], $metrics['textHeight'], $background);
        $imageText->setImageFormat('png');
        $imageText->drawImage($draw);
        $imageUsernamePath = $this->tmpDir->path('text-tmp.png');
        file_put_contents($imageUsernamePath, $imageText);

        return $imageUsernamePath;
    }

    /**
     * @param mixed $filepathScaledAvatar
     * @throws \ImagickException
     */
    protected function createMaskedAvatar(mixed $filepathScaledAvatar): void
    {
        $base = new Imagick($filepathScaledAvatar);
        $mask = new Imagick(base_path('public/images/opengraph/template/avatar-mask.png'));
        $base->compositeImage($mask, Imagick::COMPOSITE_COPYOPACITY, 0, 0);
        $base->writeImage($filepathScaledAvatar);
    }

    /**
     * @param string $fontType
     * @param string $text
     * @param int $fontSize
     * @param ImageManager $manager
     * @return string
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    protected function getTextImagePath(string $fontType, string $text, int $fontSize, ImageManager $manager): string
    {
        $imageUsernamePath = $this->getTextImageSavedPath($fontType, $text, $fontSize);
        $imageTextReady = $manager->read($imageUsernamePath);
        $scaledImageTextReady = $imageTextReady->toPng();
        $scaledImageTextReady->save($imageUsernamePath);

        return $imageUsernamePath;
    }

    public static function removeEmoji(string $string): string
    {
        return remove_emoji($string);
    }
}
