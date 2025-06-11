/*
\c postgres;
DROP DATABASE IF EXISTS haproxy_manager;
CREATE DATABASE haproxy_manager;
\c haproxy_manager;
*/
CREATE TABLE Type_log (
    niveau INTEGER NOT NULL,
    nom VARCHAR(20) NOT NULL,
    code_numerique INTEGER NOT NULL,
    PRIMARY KEY (code_numerique)
);

-- Insertion des données
INSERT INTO Type_log (niveau, nom, code_numerique) VALUES
(0, 'emerg', 0),
(1, 'alert', 1),
(2, 'crit', 2),
(3, 'err', 3),
(4, 'warn', 4),
(5, 'notice', 5),
(6, 'info', 6),
(7, 'debug', 7);


CREATE TABLE Protocoles (
    id SERIAL PRIMARY KEY,
    nom_protocole VARCHAR(100) NOT NULL,
    log VARCHAR(100)
);

-- Insertion des types de protocoles
INSERT INTO Protocoles (nom_protocole,log) VALUES
('http', 'httplog'),
('tcp', 'tcplog'),
('https', 'httplog'),
('webSocket, gRPC, etc...', 'httplog'),
('udp', 'syslog');


CREATE TABLE Algorithmes (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- Insertion des mécanismes de répartition
INSERT INTO Algorithmes (nom) VALUES
('roundrobin'),
('leastconn'),
('source'),
('uri'),
('hdr'),
('random');

