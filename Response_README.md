## Part2. Reponse Status


### Usage

```php
$ReponseStatus = new Tanel\PHPWebKit\Response\ResponseStatus();

// get status text from code
echo $ReponseStatus->getReasonPhrase(1000); // 参数无法通过验证

// get the status code by text
echo $ReponseStatus->getStatusCode('服务器异常'); // 5000

// check if status code exists
echo $ReponseStatus->hasStatusCode(1003); // true
echo $ReponseStatus->hasStatusCode(9999999); // false

// check if reason phrase exists
echo $ReponseStatus->hasReasonPhrase('参数类型不匹配'); // true
echo $ReponseStatus->hasReasonPhrase('参数类型不匹配'); // false

```

This package provides an interface with all status codes as constanst for your convenience. When developing a class that deals with HTTP status codes, simply implement the interface and start using constants instead of magic numbers for more readable and understandable code.

```php
use Tanel\PHPWebKit\Http\ResponseCodes;

class Response implements ResponseCodes {
    public function getResponseText($code) {
        static $ResponseStatus;
        if (!$ResponseStatus instanceof Tanel\PHPWebKit\Response\ResponseStatus) {
            $ResponseStatus = new Tanel\PHPWebKit\Response\ResponseStatus();
        }
        return $ReponseStatus->getReasonPhrase($code);
    }

    public function respond($code, $data) {
        $text = $this->getResponseText($code);
        return json_encode(['code' => self::PM_TYPE_MISMATCH, 'message' => $text, 'data' => $data]);
    }

    public function someMethod() {
        // ... some logic
        return respond(self::PM_TYPE_MISMATCH, $data);
    }
}
```


### Configure
If you want to localize status texts, you can supply an array when initiating the class. You may overwrite all or just some codes.
A reason phrase has to be unique and may only be used for one status code.

``` php
// add custom texts
$ResponseStatus = new Tanel\PHPWebKit\Http\ResponseStatus([
    42200 => '用户已登录',
    42201 => '用户临时锁禁',
]);
```

## Available HTTP status codes

```
/*Code  =>  Message */
1000 => '参数无法通过验证',
1001 => '参数不允许为空',
1002 => '缺少必要参数',
1003 => '参数类型不匹配',
1004 => '需要integer参数',
1005 => '需要string类型参数',
1006 => '需要array类型参数',

2001 => '插入成功',
2002 => '更新成功',
2003 => '修改成功',
2004 => '修改成功',
2005 => '删除成功',
2006 => '移除成功',

3000 => '操作失败',
3001 => '插入失败',
3002 => '更新失败',
3003 => '修改失败',
3004 => '替换失败',
3005 => '删除失败',
3006 => '移除失败',
3007 => '取消失败',
3008 => '发送失败',
3009 => '校验失败',
3010 => '请求失败',

4000 => '用户态异常',
4001 => '用户不存在',
4002 => '用户未登录',
4003 => '密码错误',
4004 => '两次密码不一致',
4005 => '用户已注册',
4006 => '用户没用权限',
4007 => '用户授权口令错误',

5000 => '服务器异常',
5001 => '服务器超时',

6000 => '接口异常',
6001 => '接口不存在',
6002 => '接口请求方式错误',
6003 => '接口版本不支持',
6004 => '接口超时',
```