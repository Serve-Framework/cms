<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\wrappers\providers;

use cms\wrappers\Visitor;

/**
 * CRM Visitor Provider.
 *
 * @author Joe J. Howard
 */
class LeadProvider extends Provider
{
    /**
     * {@inheritdoc}
     */
    public function create(array $row): Visitor
    {
        $visitor = new Visitor($this->SQL, $row);

        $visitor->regenerateId();

        $visitor->save();

        return $visitor;
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
    public function byKey(string $key, $value, bool $single = true)
    {
        if ($single)
        {
            $row = $this->SQL->SELECT('*')->FROM('crm_visitors')->WHERE($key, '=', $value)->ROW();

            if ($row)
            {
                return new Visitor($this->SQL, $row);
            }

            return null;
        }
        else
        {
            $visitors = [];

            $rows = $this->SQL->SELECT('*')->FROM('crm_visitors')->WHERE($key, '=', $value)->FIND_ALL();

            if ($rows)
            {
                foreach ($rows as $row)
                {
                    $visitors[] = new Visitor($this->SQL, $row);
                }
            }

            return $visitors;
        }
    }

    /**
     * Get all visitors.
     *
     * @return array
     */
    public function all(): array
    {
        $visitors = [];

        $rows = $this->SQL->SELECT('*')->FROM('crm_visitors')->FIND_ALL();

        if ($rows)
        {
            foreach ($rows as $row)
            {
                $visitors[] = new Visitor($this->SQL, $row);
            }
        }

        return $visitors;
    }

    /**
     * Get all leads.
     *
     * @return array
     */
    public function leads(): array
    {
        $visitors = [];

        $rows = $this->SQL->SELECT('*')->FROM('crm_visitors')->WHERE('email', '!=', '')->FIND_ALL();

        if ($rows)
        {
            foreach ($rows as $row)
            {
                $visitors[] = new Visitor($this->SQL, $row);
            }
        }

        return $visitors;
    }
}
