<?php

namespace Tanel\PHPWebKit\Http;

interface ResponseCodes {
    /**
     * Response code as a constant.
     * 1xxx 参数异常
     * 2xxx 操作成功
     * 3xxx 操作失败
     * 4xxx 用户异常
     * 5xxx 服务状态
     * 6xxx 接口状态
     */
    const PM_INVALID         = 1000; //参数无法通过验证
    const PM_NOT_ALLOW_EMPTY = 1001; //参数不允许为空
    const PM_MISSING         = 1002; //缺少必要参数
    const PM_TYPE_MISMATCH   = 1003; //参数类型不匹配
    const PM_REQUIRE_INT     = 1004; //需要integer参数
    const PM_REQUIRE_STRING  = 1005; //需要string类型参数
    const PM_REQUIRE_ARRAY   = 1006; //需要array类型参数

    //2xxx
    const SUCC_INSERT  = 2001; //插入成功
    const SUCC_UPDATE  = 2002; //更新成功
    const SUCC_CHANGE  = 2003; //修改成功
    const SUCC_REPLACE = 2004; //删除成功
    const SUCC_DELETE  = 2005; //删除成功
    const SUCC_REMOVE  = 2005; //移除成功

    //3xxx
    const FAIL_OPRATE  = 3000; //操作失败
    const FAIL_INSERT  = 3001; //插入失败
    const FAIL_UPDATE  = 3002; //更新失败
    const FAIL_CHANGE  = 3003; //修改失败
    const FAIL_REPLACE = 3004; //删除失败
    const FAIL_DELETE  = 3005; //删除失败
    const FAIL_REMOVE  = 3006; //移除失败
    const FAIL_CANCEL  = 3007; //取消失败
    const FAIL_SEND    = 3008; //发送失败
    const FAIL_CHECK   = 3009; //校验失败
    const FAIL_REQUEST = 3010; //请求失败

    //4xxx
    const USER_EXCEPTION    = 4000; //用户态异常
    const USER_NOT_FOUND    = 4001; //用户不存在
    const USER_NOT_LOGININ  = 4002; //用户未登录
    const USER_PWD_ERROR    = 4003; //密码错误
    const USER_PWD_DIFF     = 4004; //两次密码不一致
    const USER_IS_EXIST     = 4005; //用户已注册
    const USER_NO_AUTH      = 4006; //用户没用权限
    const USER_TOKEN_ERROR  = 4007; //用户授权口令错误

    //5xxx
    const SERVER_EXCEPTION = 5000; //服务器异常
    const SERVER_TIMEOUT   = 5001;
    const SERVER_OUT_LIMIT = 5003;

    //6xxx
    const API_EXCEPTION    = 6000; //接口异常
    const API_NOT_FOUND    = 6001; //接口不存在
    const API_METHOD_ERROR = 6002; //接口请求方式错误
    const API_VERSION_EROR = 6003; //版本不支持
    const API_TIMEOUT      = 6004; //接口超时
}