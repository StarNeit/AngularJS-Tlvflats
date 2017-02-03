<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 25/12/15
 * Time: 12:10
 */

namespace AppBundle\Entity;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UploadedImages
{

    private $file;
    /**
     * Sets file.
     *
     * @param array $file
     */
    public function setFile(array $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return array
     */
    public function getFile()
    {
        return $this->file;
    }

    public function upload(Property $property)
    {
        // the file property can be empty if the field is not required
        $files = $this->getFile();
        if (null === $files || empty($files)) {
            return null;
        }

        $res = array();

        /** @var UploadedFile $file */
        foreach ($files as $file) {
            // use the original file name here but you should
            // sanitize it at least to avoid any security issues

            // move takes the target directory and then the
            // target filename to move to
            $fileName = $this->generateUniqueName($file);
            $file->move(
                $this->getUploadRootDir(),
                $fileName
            );

            // set the path property to the filename where you've saved the file
            $newImage = new PropertyImage();
            $newImage->setProperty($property);
            $newImage->setPath($fileName);

            $newImage->setUrl('/' . $this->getUploadDir() . '/' . $newImage->getPath());

            array_push($res, $newImage);
        }

        return $res;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }

    private function generateUniqueName(UploadedFile $file)
    {
        return uniqid("img_").".".$file->getClientOriginalExtension();
    }

}