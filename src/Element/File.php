<?php

namespace Elixir\Form\Element;

use Elixir\Filter\FilterInterface;
use Elixir\Form\FormEvent;
use Elixir\Form\FormInterface;
use Elixir\STDLib\Facade\I18N;
use Elixir\STDLib\MessagesCatalog;
use Elixir\STDLib\MessagesCatalogAwareTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class File extends FieldAbstract implements FileInterface
{
    use MessagesCatalogAwareTrait;
    
    /**
     * @var string
     */
    const FILE_NOT_UPLOADED = 'file_not_uploaded';

    /**
     * @var string
     */
    const UPLOAD_ERROR = 'upload_error';
    
    /**
     * @var int
     */
    const FILTER_UPLOAD = 4;
    
    /**
     * @var callable
     */
    protected $uploaderFactory = 'Elixir\HTTP\UploadedFile::create';

    /**
     * @var array|null
     */
    protected $uploaders;

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
    public function getDefaultCatalogMessages()
    {
        return [
            self::FILE_NOT_UPLOADED => I18N::__('The file is not uploaded.', ['context' => 'elixir']),
            self::UPLOAD_ERROR => I18N::__('An error occurred during upload.', ['context' => 'elixir'])
        ];
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
     * @return array
     */
    public function getUploaders()
    {
        if (null === $this->uploaders && isset($_FILES[$this->name])) {
            $this->uploaders = (array) call_user_func_array($this->uploaderFactory, [$_FILES[$this->name]]);
        }

        return $this->uploaders;
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
        return count($this->getUploaders()) > 1;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($args = null)
    {
        if ($this->prepared && (!isset($args['force']) || true !== $args['force']))
        {
            return;
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
        if (count($this->getUploaders()) > 0) {
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
        return count($this->getUploaders()) === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($data = null, array $options = [])
    {
        $this->resetValidation();
        $this->dispatch(new FormEvent(FormEvent::VALIDATE_ELEMENT));

        if (!$this->messagesCatalog) {
            $this->setMessagesCatalog(MessagesCatalog::instance());
        }
        
        foreach ($this->getUploaders() as $uploader) {
            $this->validationErrors = [];

            switch ($uploader->getError()) {
                case UPLOAD_ERR_OK:
                    if ($uploader->isUploaded()) {
                        foreach ($this->validators as $config) {
                            $validator = $config['validator'];
                            $o = $config['options'] + $options;

                            $valid = $validator->validate($uploader, $o);

                            if (!$valid) {
                                $this->validationErrors = array_merge($this->validationErrors, $validator->getErrors());

                                if ($this->breakChainValidationOnFailure) {
                                    break;
                                }
                            }
                        }
                    } else {
                        $this->validationErrors = [$this->messagesCatalog->get(self::FILE_NOT_UPLOADED)];
                    }
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                case UPLOAD_ERR_PARTIAL:
                case UPLOAD_ERR_NO_FILE:
                default:
                    $this->validationErrors = [$this->messagesCatalog->get(self::UPLOAD_ERROR)];
            }
        }

        $this->validationErrors = array_unique($this->validationErrors);

        return $this->hasError();
    }

    /**
     * {@inheritdoc}
     */
    public function addFilterUpload(FilterInterface $filter, array $options = [])
    {
        $options[self::FILTER_MODE] = self::FILTER_UPLOAD;
        $this->addFilter($filter, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function filter($data = null, array $options = [])
    {
        $type = self::FILTER_UPLOAD;

        foreach ($this->getUploaders() as $uploader) {
            if ($uploader->isUploaded()) {
                foreach ($this->filters as $config) {
                    if (($config['options'][self::FILTER_MODE] & $type) === $type) {
                        $o = $config['options'] + $options;
                        $data = $config['filter']->filter($uploader, $o);
                    }
                }
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function receive($targetPath = null)
    {
        $this->filter();
        $values = [];
        
        foreach ($this->getUploaders() as $uploader) {
            $uploader->moveTo($targetPath);
            $values[] = $uploader->getClientFilename();
        }
        
        $this->setValue(count($values) > 0 ? $values[0] : $values, self::VALUE_RAW);
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->uploaders = null;
        parent::reset();
    }
}
