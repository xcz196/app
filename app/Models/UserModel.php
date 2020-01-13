<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    public $primaryKey='user_id';
    /**
     * 关联到模型的数据表
     ** @var string
     */
    protected $table = 'user';

    /**
     * 表明模型是否应该被打上时间戳
     ** @var bool
     */
    public $timestamps = false;



    /**
     * 可以被批量赋值的属性. ** @var array
     */
    protected $guarded = [];//黑名单
}
