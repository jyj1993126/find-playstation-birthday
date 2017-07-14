[For English](#find-playstation-birthday)

# [![Playstation][playstation-image]][playstation-url] 找回PSN生日
> 帮助通过重设密码时的生日验证

基于 Apache Licence 2.0 发布

## 前提条件
- 需要拥有账号对应邮箱的登录权限（ 用于接收playstation发送的重设密码链接 ）
- 电脑上装有对应程序的运行环境 （如果不会使用可以点击这里寻求付费服务）

## 使用方法
- PHP

## 原理
  暴力提交所有可能的生日日期，一旦日期正确，请求会被302重定向到重设密码的地址。
  需要注意的是:
  1. 提交需带上cookie。
  2. 用于提交的blah_token每次都会改变，需要从返回的页面中获取。
  3. 缩短日期范围会有效减少找回生日的时间。

## 关于我
Leon J – [@博客](http://www.leonj.cc/) – jyj1993126@gmail.com

[https://github.com/jyj1993126](https://github.com/jyj1993126)

## 贡献
> 修复bug & 不同语言实现

1. Fork it (<https://github.com/jyj1993126/find-playstation-birthday/fork>)
2. 切新的功能分支 (`git checkout -b feature/fooBar`)
3. 提交变更 (`git commit -am 'Add some fooBar'`)
4. 推到远程仓库 (`git push origin feature/fooBar`)
5. 创建 Pull Request

# Find Playstation Birthday
> Help to pass through birthday validation on reseting password

Distributed under the Apache Licence 2.0

## Requirements
- with access of logging into the email of your account ( for the  password-reseting link sended by playstation )
- with runtime environment for specified program

## How to use
- [PHP](php/README.md)


## About Me
Leon J – [@Blog](http://www.leonj.cc/) – jyj1993126@gmail.com

[https://github.com/jyj1993126](https://github.com/jyj1993126)

## Contributing
> fix bugs & implementation of different language

1. Fork it (<https://github.com/jyj1993126/find-playstation-birthday/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

<!-- Markdown link & img dfn's -->
[playstation-image]: playstation.png
[playstation-url]: https://account.sonyentertainmentnetwork.com/liquid/external/forgot-password!input.action
