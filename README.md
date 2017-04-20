# Simple Yii2
深入分析、理解Yii2架构，模仿写的一个简化版。

### 前端说明
* 使用sass编写
* 组件管理使用bower


### 使用七牛做CDN
* 本地参数文件需以下配置
```
return [
    'cdn' => [
        'staticUrl' => '',
        'bucket' => [
            'default' => ''
        ],
        'accessKey' => '',
        'secretKey' => ''
    ]
];
```

### Yii2中db模块关系图
![Yii2中db模块关系图](https://static.zgjian.cc/post/Analyze_yii2_database_layout.png)