<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use Hyperf\Redis\RedisFactory;

class IndexController extends AbstractController
{
    public function index(RedisFactory $redisFactory)
    {
        $redis = $redisFactory->get('default');

        $redisKey = 'hyPerf:number';

        // 循环Redis插入1000条数据
        $items = [];
        for ($i = 0; $i < 1000; $i++) {
            $data = $redis->get("{$redisKey}:{$i}");
            if ($data) {
                $redis->del("{$redisKey}:{$i}");
            }
            $redis->set("{$redisKey}:{$i}", "value:{$i}", ['EX' => 1800]);
        }

        // 获取Redis中的数据

        for ($i = 0; $i < 1000; $i++) {
            $items[] = $redis->get("{$redisKey}:{$i}");
        }

        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
            'data' => $items,
            'code' => 200,
        ];
    }
}
