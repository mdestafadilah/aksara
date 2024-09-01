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

class AppUsersOauth extends Migration
{
    public function up()
    {
        // Add columns to table
        $this->forge->addField([
            'user_id' => [
                'type' => 'bigint',
                'constraint' => 22,
                'unsigned' => true,
                'null' => false
            ],
            'service_provider' => [
                'type' => 'varchar',
                'constraint' => 32,
                'null' => false
            ],
            'access_token' => [
                'type' => 'varchar',
                'constraint' => 255,
                'null' => false
            ],
            'status' => [
                'type' => 'tinyint',
                'constraint' => 1,
                'default' => '0',
                'null' => false
            ]
        ]);

        // Add primary and unique index
        $this->forge->addKey('user_id', false, true);
        $this->forge->addKey('service_provider', false, true);

        // Add foreign key to parent table
        $this->forge->addForeignKey('user_id', 'app__users', 'user_id', 'CASCADE', 'CASCADE');

        // Create table
        $this->forge->createTable('app__users_oauth');
    }

    public function down()
    {
        // Safe abstraction
    }
}
