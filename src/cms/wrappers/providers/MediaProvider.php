<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\wrappers\providers;

use cms\wrappers\Media;
use serve\database\builder\Builder;

/**
 * Media provider.
 *
 * @author Joe J. Howard
 */
class MediaProvider extends Provider
{
    /**
     * Assoc array of thumbnail sizes.
     *
     * @var array
     */
    private $thumbnailSizes;

    /**
     * Override inherited constructor.
     *
     * @param \serve\database\builder\Builder $SQL            SQL query builder
     * @param array                                   $thumbnailSizes Assoc array of thumbnail sizes
     */
    public function __construct(Builder $SQL, array $thumbnailSizes)
    {
        $this->SQL = $SQL;

        $this->thumbnailSizes = $thumbnailSizes;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $row)
    {
        $media = new Media($this->SQL, $this->thumbnailSizes, $row);

        if ($media->save())
        {
            return $media;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function byId(int $id)
    {
    	return $this->byKey('id', $id, true);
    }

    /**
     * {@inheritdoc}
     */
    public function byKey(string $key, $value, bool $single = false)
    {
    	if ($single)
        {
    		$row = $this->SQL->SELECT('*')->FROM('media_uploads')->WHERE($key, '=', $value)->ROW();

    		if ($row)
            {
                return new Media($this->SQL, $this->thumbnailSizes, $row);
            }

            return false;
    	}
    	else
        {
            $media = [];

    		$rows = $this->SQL->SELECT('*')->FROM('media_uploads')->WHERE($key, '=', $value)->FIND_ALL();

            if ($rows)
            {
        		foreach ($rows as $row)
                {
                    $media[] = new Media($this->SQL, $this->thumbnailSizes, $row);
                }
            }

            return $media;
    	}
    }

    /**
     * Get all media objects.
     *
     * @return array
     */
    public function all(): array
    {
        $media = [];

        $rows = $this->SQL->SELECT('*')->FROM('media_uploads')->FIND_ALL();

        if ($rows)
        {
            foreach ($rows as $row)
            {
                $media[] = new Media($this->SQL, $this->thumbnailSizes, $row);
            }
        }

        return $media;
    }
}
