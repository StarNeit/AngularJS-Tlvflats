<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 12.07.2015
 * Time: 19:56
 */

namespace AppBundle\Entity;

use Imagick;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 *
 * @ExclusionPolicy("all")
 */
class PropertyImage
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="images")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    protected $property;

    /**
     * @ORM\Column(type="text")
     *
     * @Expose
     */
    protected $url;

    /**
     * @VirtualProperty()
     * @SerializedName("smallUrl")
     * @return string
     */
    public function getSmallUrl()
    {
        return "/".$this->getUploadDir()."/small/".$this->getPath();
    }

    /**
     * @VirtualProperty()
     * @SerializedName("largeUrl")
     * @return string
     */
    public function getLargeUrl()
    {
        return "/".$this->getUploadDir()."/large/".$this->getPath();
    }

    /**
     * @ORM\Column(type="text")
     */
    protected $path;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Expose
     */
    protected $description;


    protected $remove = false;

    protected $regenerate_thumbnail = false;

    /**
     * @return boolean
     */
    public function isRegenerateThumbnail()
    {
        return $this->regenerate_thumbnail;
    }

    /**
     * @param boolean $regenerate_thumbnail
     */
    public function setRegenerateThumbnail($regenerate_thumbnail)
    {
        $this->regenerate_thumbnail = $regenerate_thumbnail;
    }

    /**
     * @return mixed
     */
    public function getRemove()
    {
        return $this->remove;
    }

    /**
     * @param mixed $remove
     */
    public function setRemove($remove)
    {
        $this->remove = $remove;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return PropertyImage
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return PropertyImage
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set property
     *
     * @param \AppBundle\Entity\Property $property
     * @return PropertyImage
     */
    public function setProperty(\AppBundle\Entity\Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \AppBundle\Entity\Property 
     */
    public function getProperty()
    {
        return $this->property;
    }

    public function removeFiles() {
        @unlink($this->getUploadRootDir().'/'.$this->getPath());
        @unlink($this->getUploadRootDir().'/small/'.$this->getPath());
        @unlink($this->getUploadRootDir().'/large/'.$this->getPath());
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

    /**
     * Set description
     *
     * @param string $description
     * @return PropertyImage
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function removeThumbnails()
    {
        @unlink($this->getUploadRootDir().'/small/'.$this->getPath());
        @unlink($this->getUploadRootDir().'/large/'.$this->getPath());
    }

    public function generateThumbnails($aspect_ratio, $aspect_threshold, $small_height, $large_height)
    {
        $this->ensureFolders();
        $img = $this->fixAspect($aspect_ratio, $aspect_threshold);
        $this->saveThumbnail($this->getUploadRootDir() . "/small", $img->clone(), $small_height);
        $this->saveThumbnail($this->getUploadRootDir() . "/large", $img->clone(), $large_height);
        $img->clear();
    }

    private function saveThumbnail($target_folder, \Imagick $img, $height) {
        if($img->getImageHeight() > $height){
            $img->resizeImage(0,$height,Imagick::FILTER_LANCZOS,1);
        }
        $img->setImageCompression(Imagick::COMPRESSION_JPEG);
        $img->setImageCompressionQuality(75);
        $img->stripImage();

        $img->writeImage($target_folder."/".$this->getPath());
        $img->clear();
    }

    private function ensureFolder($path) {
        if(is_file($path)) {
            unlink($path);
        }

        if(!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    private function ensureFolders()
    {
        $this->ensureFolder($this->getUploadRootDir() . "/small");
        $this->ensureFolder($this->getUploadRootDir() . "/large");
    }

    private function fixAspect($aspect_ratio, $aspect_threshold)
    {
        $img = new \Imagick($this->getUploadRootDir()."/".$this->getPath());
        $width = $img->getImageWidth();
        $height = $img->getImageHeight();
        $curr_aspect = $height/$width;
        //good enough. won't crop
        if($curr_aspect>$aspect_ratio*(1 - $aspect_threshold) && $curr_aspect<$aspect_ratio*(1 + $aspect_threshold)) {
            return $img;
        }

        if($width*$aspect_ratio > $height) {
            $img->cropImage(floor($height/$aspect_ratio), $height, floor(($width-$height/$aspect_ratio)/2),0);
        } else {
            $img->cropImage($width, floor($width*$aspect_ratio), 0,floor(($height - $width*$aspect_ratio)/2));
        }
        return $img;
    }

    public function fileMissing()
    {
        return !is_file($this->getUploadRootDir()."/".$this->getPath());
    }
}
