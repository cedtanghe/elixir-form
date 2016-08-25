<?php

namespace Elixir\Form\Element;

use Elixir\Form\FormInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class File extends FieldAbstract implements FileInterface
{
    /**
     * @var callable
     */
    protected $uploaderFactory = 'Elixir\HTTP\UploadedFileWithControls::create';

    /**
     * @var array
     */
    protected $uploaders = [];

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
     * @return callable
     */
    public function setUploaderFactory($value)
    {
        $this->uploaderFactory = $value;
    }

    /**
     * @return callable
     */
    public function getUploaderFactory()
    {
        return $this->uploaderFactory;
    }

    /**
     * @param string $id
     * @param string $value
     */
    public function setAPCUploadProgressData($id, $value = null)
    {
        $this->setOption('APC_UPLOAD_PROGRESS_DATA', ['id' => $id, 'value' => $value ?: uniqid()]);
    }

    /**
     * @return array|null
     */
    public function getAPCUploadProgressData()
    {
        return $this->getOption('APC_UPLOAD_PROGRESS_DATA');
    }

    /**
     * @param int $value
     */
    public function setMaxFileSize($value)
    {
        $this->setOption('MAX_FILE_SIZE', $value);
    }

    /**
     * @return int|null
     */
    public function getMaxFileSize()
    {
        return $this->getOption('MAX_FILE_SIZE');
    }

    /**
     * {@inheritdoc}
     */
    public function hasMultipleFiles()
    {
        return count($this->uploaders) > 1;
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

        if ($root instanceof FormInterface) {
            $root->setAttribute('enctype', FormInterface::ENCTYPE_MULTIPART);
        }

        return parent::prepare($args);
    }

    /**
     * {@inheritdoc}
     */
    public function isUploaded()
    {
        if (count($this->uploaders) > 0) {
            foreach ($this->uploaders as $uploader) {
                if (!$uploader->isUploaded()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        // Todo
    }

    /**
     * {@inheritdoc}
     */
    public function validate($data = null, array $options = [])
    {
        // Todo
    }

    /**
     * {@inheritdoc}
     */
    public function filter($data = null, array $options = [])
    {
        // Todo
    }

    /**
     * {@inheritdoc}
     */
    public function receive($targetPath = null)
    {
        $receive = true;

        if ($this->isUploaded()) {
        }

        return $receive;
    }
    
    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        // Todo
    }
}
