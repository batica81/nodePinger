create table liveclient
(
    id        int auto_increment primary key,
    client_ip varchar(25)                         not null,
    is_alive  int       default 0                 not null,
    timestamp timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    rtt       int                                 null
)
    collate = utf8mb4_unicode_520_ci;

