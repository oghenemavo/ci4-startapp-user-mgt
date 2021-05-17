<?php

namespace Ecosystem\Authentication\Libraries;

use Config\Database;
use Ecosystem\Authentication\Models\MailTemplate;

class MailTemplateLib 
{
    /**
     * Find a Mail Template by slug
     *
     * @param string $for
     * @return void
     */
    public function find_template(string $for)
    {
        $template = new MailTemplate();
        return $template->where('for_slug', $for)->first();
    }

    public function find_template_by_id(int $id)
    {
        $template = new MailTemplate();
        return $template->where('id', $id)->first();
    }

    /**
     * Get all Mail Templates
     *
     * @return void
     */
    public function get_templates()
    {
        $template = new MailTemplate();
        return $template->findAll();
    }

    /**
     * Update an existing mail template
     *
     * @param array $data
     * @return void
     */
    public function update_template(array $data)
    {
        $result = [];

        $db = Database::connect(); // create a database connection

        $db->transStart(); // start transaction automatically

        $template = new MailTemplate();

        // Insert into user table
        try {
            $template->save($data);
        } catch (\ReflectionException $e) {
        }

        $db->transComplete(); // complete transaction

        if ($db->transStatus() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            $result['error'] = 'Unable to sign up user';
        } else {
            $result['success'] = true;
        }
        return $result;
    }
}