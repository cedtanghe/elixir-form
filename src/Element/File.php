<?php

namespace Elixir\Form\Element;

use Elixir\Form\FormInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class File extends FieldAbstract implements FileInterface
{
    /**
     * @var UploadedFileInterface 
     */
    protected $uploader;
    
    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->setName($name);
        }
        
        $this->helper = 'file';
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasMultipleFiles()
    {
        // Todo
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($args = null)
    {
        if (null === $this->helper) {
            $this->setHelper('file');
        }
        
        $root = $this->getRootElement();
        
        if ($root instanceof FormInterface){
            $root->setAttribute('enctype', FormInterface::ENCTYPE_MULTIPART);
        }
        
        return parent::prepare($args);
    }
    
    /**
     * {@inheritdoc}
     */
    public function isUploaded()
    {
        // Todo
    }

    /**
     * {@inheritdoc}
     */
    public function receive()
    {
        // Todo
    }
}
