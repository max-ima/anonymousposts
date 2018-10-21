<?php
/**
*
* phpBB Extension - toxyy Anonymous Posts
* @copyright (c) 2018 toxyy <thrashtek@yahoo.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace toxyy\anonymousposts\migrations;

class v_0_6_1_data extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\toxyy\anonymousposts\migrations\v_0_6_0');
	}

	/**
	 * Add or update data in the database
	 *
	 * @return array Array of table data
	 * @access public
	 */
        public function update_data()
        {
                return array(
                        // update the new anonymous_index column in the posts table with their values, if you had this extension installed already
                        array('custom', array(array($this, 'update_anonymous_index'))),
		);
        }

        // see above
	public function update_anonymous_index()
	{
                $sql = 'UPDATE ' . POSTS_TABLE . ' p
                        INNER JOIN( SELECT IF(@super = p.topic_id, @count := @count + 1, @count := 1) as anon_index, (@super := p.topic_id) as tid, (p.post_id) as pid
                                    FROM phpbb_posts as p
                                    JOIN (SELECT @super := 0) as tmp
                                    JOIN (SELECT @count := 0) as tmp2
                                    WHERE p.is_anonymous = 1
                                    ORDER BY post_time ASC
                        ) AS q ON q.pid = p.post_id
                        SET p.anonymous_index = q.anon_index';
                $this->db->sql_query($sql);
	}
}
