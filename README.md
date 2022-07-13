# 简介

这是个使用Hyperf框架搭建的一个简易的webSocket聊天系统。

# 部署

需要监听9501和9501

* nignx配置
```
upstream web {
    server 127.0.0.1:9501;
}
upstream websocket {
    server 127.0.0.1:9502;
}
server
{
    listen 80;
    server_name hyperf-base.com;
        
    add_header Access-Control-Allow-Origin *;
    add_header Access-Control-Allow-Methods 'GET, POST, OPTIONS';
    add_header Access-Control-Allow-Headers 'DNT,Keep-Alive,User-Agent,Cache-Control,Content-Type,Authorization';          
    #WebSocket
    location /ws {
        # WebSocket Header
        proxy_http_version 1.1;
        proxy_set_header Upgrade websocket;
        proxy_set_header Connection "Upgrade";
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Host $http_host;
        proxy_read_timeout 60s ;
        
        proxy_pass http://websocket;
    }
     
    #web 
    location / {
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        
        
        proxy_pass http://web;
    }
    #access_log  /home/wwwlogs/hyperf.log main_log;
}
```

# 配置

数据库文件在项目根目录下，导入数据库，修改好连接配置即可。

# 开启服务

在项目根目录下启动hyperf, linux 输入命令：
```
php bin/hyperf.php start
```

# 测试访问

* 基础后台地址：
  http://hyperf-base.com/admin/
* webSocket地址：
  ws://hyperf-base.com/ws/
* 账号密码
  后台：admin  admin123 <br/>
  用户1：15913137111   123123<br/>
  用户2：15913137222   123123<br/>

# 前端聊天模板文件git
https://github.com/jenonWu/webWeChat  
