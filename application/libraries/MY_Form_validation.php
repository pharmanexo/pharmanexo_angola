<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    /**
     * Checks that a value is unique in the database.
     *
     * i.e. '…|required|unique[users.name,users.id]|trim…'
     *
     * <code>
     * "unique[tablename.fieldname,tablename.(primaryKey-used-for-updates)]"
     * </code>
     *
     * @param mixed $value  The value to be checked.
     * @param mixed $params The table and field to check against, if a second
     * 
     * field is passed in this is used as "AND NOT EQUAL".
     *
     * @return bool True if the value is unique for that field, else false.
     */
    public function unique($value, $params)
    {
        $CI = &get_instance();
        $CI->load->database();

        // Allow for more than 1 parameter.
        $fields = explode(',', $params);

        // Extract the table and field from the first parameter.
        list($table, $field) = explode('.', $fields[0], 2);

        $this->CI->db->select($field);
        $this->CI->db->from($table);
        $this->CI->db->where($field, $value);
        $this->CI->db->limit(1);

        if (isset($fields[1])) {
            // Extract the table and field from the second parameter
            list($where_table, $where_field) = explode('.', $fields[1], 2);

            // Get the value from the post's $where_field. If the value is set,
            // add "AND NOT EQUAL" where clause.
            $where_value = $this->CI->input->post($where_field);
            if (isset($where_value)) {
                $this->CI->db->where("{$where_table}.{$where_field} <>", $where_value);
            }
        }

        $query = $this->CI->db->get();

        if ($query->row()) {
            $this->CI->form_validation->set_message('unique', $this->CI->lang->line('form_validation_is_unique'));
            return false;
        }

        return true;
    }
}

/* End of file: MY_Form_validation.php */
