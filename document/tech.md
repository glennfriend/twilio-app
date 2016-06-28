##Time (時間) & Timezone (時區)
```txt
如果有跨國的時區問題, 請仔細思考你想要怎麼著手

如果要傳入 timestamp 時間戳記
那麼你必須了解它一定是 UTC 時區
timestamp === UTC

如果要傳入已經處理好的格式 yyyy-mm-dd
那麼就照這個 "string" 存入資料庫
不要另外做轉換
```
