<?php

/**
 * Description of ACLUserModel
 *
 * @author Steven <steven.php@gmail.com>
 */
class ACLUserModel extends BaseModel
{
    public function get_list ($page_no = 1, $params = null)
    {
        $select = $this->db->select()
                ->from(DBTables::USER)
                ->order('reg_time DESC');

        $pager = new Pager($this->db, $select);
        $sql = $pager->get_page($page_no);

        $ret = new stdClass();
        $ret->data = $this->db->fetchAll($sql);
        $ret->pager = $pager;

        return $ret;
    }

    public function delete ($id)
    {
        $this->db->delete(DBTables::USER, $this->db->quoteInto('id=?', $id));
    }
}

