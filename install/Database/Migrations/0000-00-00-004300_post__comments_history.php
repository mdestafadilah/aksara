<?php

/**
 * This file is part of Aksara CMS, both framework and publishing
 * platform.
 *
 * @author     Aby Dahana <abydahana@gmail.com>
 * @copyright  (c) Aksara Laboratory <https://aksaracms.com>
 * @license    MIT License
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.txt file.
 *
 * When the signs is coming, those who don't believe at "that time"
 * have only two choices, commit suicide or become brutal.
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PostCommentsHistory extends Migration
{
    public function up()
    {
        // Add columns to table
        $this->forge->addField([
            'comment_id' => [
                'type' => 'bigint',
                'constraint' => 22,
                'unsigned' => true,
                'null' => false
            ],
            'comments' => [
                'type' => 'text',
                'null' => false
            ],
            'attachment' => [
                'type' => 'varchar',
                'constraint' => 255,
                'null' => false
            ],
            'timestamp' => [
                'type' => 'timestamp',
                'null' => false
            ]
        ]);

        // Add primary and unique index
        $this->forge->addKey('comment_id', false, false);

        // Add foreign key to parent table
        $this->forge->addForeignKey('comment_id', 'post__comments', 'comment_id', 'CASCADE', 'CASCADE');

        // Create table
        $this->forge->createTable('post__comments_history');
    }

    public function down()
    {
        // Safe abstraction
    }
}
