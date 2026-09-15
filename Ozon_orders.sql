CREATE TABLE Заказ 
(
    Posting_id TEXT UNIQUE,            # id обращения 
    raw_payload TEXT,                  # весь json
    raw_headers TEXT,                  # http заголовок запроса
    created_at DATETIME DEFAULT NOW(),
    processed_at DATETIME
)


