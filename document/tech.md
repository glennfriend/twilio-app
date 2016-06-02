***時間
```txt
從 frontend (browser) 傳進 backend (PHP) 的時間, 必須是 UTC 時區
由 backend 轉為相對應 (PHP環境設定) 的時區
PHP 程式的時區可以自由設定
backend 傳出 時間 給 frontend 時, 必須轉為 UTC 時區

傳入與輸出規則:
格式: date int
時區: UTC
```
