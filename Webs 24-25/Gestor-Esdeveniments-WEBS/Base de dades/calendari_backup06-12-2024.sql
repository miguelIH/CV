--
-- PostgreSQL database dump
--

-- Dumped from database version 16.6 (Ubuntu 16.6-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.6 (Ubuntu 16.6-0ubuntu0.24.04.1)

-- Started on 2024-12-06 15:21:48 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 222 (class 1259 OID 16422)
-- Name: events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.events (
    id_event bigint NOT NULL,
    nom_events text NOT NULL,
    descripcio text,
    data_inici timestamp with time zone NOT NULL,
    data_fi timestamp with time zone NOT NULL,
    id_lloc bigint,
    id_organizador bigint,
    color text
);


ALTER TABLE public.events OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16421)
-- Name: events_id_event_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.events ALTER COLUMN id_event ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.events_id_event_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 218 (class 1259 OID 16403)
-- Name: llocs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.llocs (
    id_lloc bigint NOT NULL,
    localitat text NOT NULL,
    provincia text NOT NULL,
    capacitat integer,
    CONSTRAINT llocs_capacitat_check CHECK ((capacitat > 0))
);


ALTER TABLE public.llocs OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16402)
-- Name: llocs_id_lloc_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.llocs ALTER COLUMN id_lloc ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.llocs_id_lloc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 220 (class 1259 OID 16412)
-- Name: organizadors; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.organizadors (
    id_organizador bigint NOT NULL,
    nom text NOT NULL,
    email text NOT NULL,
    telefon text
);


ALTER TABLE public.organizadors OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16411)
-- Name: organizadors_id_organizador_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.organizadors ALTER COLUMN id_organizador ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.organizadors_id_organizador_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 224 (class 1259 OID 16483)
-- Name: ressenyes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ressenyes (
    id_ressenyes bigint NOT NULL,
    id_event bigint,
    id_usuari bigint,
    puntuacio integer,
    comentari text,
    data_ressenya timestamp with time zone DEFAULT now(),
    nomlloc character varying(255),
    CONSTRAINT ressenyes_puntuacio_check CHECK (((puntuacio >= 1) AND (puntuacio <= 5)))
);


ALTER TABLE public.ressenyes OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16482)
-- Name: ressenyes_id_ressenyes_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.ressenyes ALTER COLUMN id_ressenyes ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.ressenyes_id_ressenyes_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 216 (class 1259 OID 16391)
-- Name: usuaris; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuaris (
    id_usuari bigint NOT NULL,
    nom text NOT NULL,
    email text NOT NULL,
    poble text,
    password text NOT NULL,
    data_registre timestamp with time zone DEFAULT now(),
    data_naixament date
);


ALTER TABLE public.usuaris OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 16390)
-- Name: usuaris_id_usuari_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.usuaris ALTER COLUMN id_usuari ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.usuaris_id_usuari_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- TOC entry 3441 (class 0 OID 16422)
-- Dependencies: 222
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.events (id_event, nom_events, descripcio, data_inici, data_fi, id_lloc, id_organizador, color) FROM stdin;
11	Correfocs	Una festa popular catalana on diables i bestiari fantàstic recorren els carrers amb pirotècnia i tambors, creant un ambient màgic i espectacular. Se celebra a molts pobles i ciutats, sovint durant festes majors.	2024-11-28 21:00:00+00	2024-11-28 22:30:00+00	12	12	#20dfdc
4	Festa escuma	La festa de l'escuma és un estil de celebració estiuenc que consisteix a ballar al ritme d'una música específica (d'acord amb la temàtica i edat dels assistents) mentre els participants se submergeixen en muntanyes fetes amb escuma, resultant així molt refrescant i divertida.	2024-11-28 13:00:00+00	2024-11-28 15:45:00+00	2	2	#d35555
12	Nit de Jazz	Una vetllada màgica amb música en viu de les millors bandes de jazz de Catalunya, sota el cel estrellat. Inclou zona gastronòmica.	2024-12-15 21:00:00+00	2024-12-15 23:30:00+00	13	13	#609bfb
3	Saló manga	Un esdeveniment anual dedicat al manga, l'anime i la cultura japonesa, amb estands, concursos, exhibicions, tallers i convidats especials. És un punt de trobada per a fans d'arreu del món.	2024-12-10 09:00:00+00	2024-12-12 15:00:00+00	1	1	#f58f00
13	Fira Nadal	Una mostra única de productes artesanals per Nadal, amb tallers per a famílies, degustacions de dolços típics i decoració nadalenca.	2024-12-10 16:00:00+00	2024-12-10 21:00:00+00	14	14	#d3bb64
\.


--
-- TOC entry 3437 (class 0 OID 16403)
-- Dependencies: 218
-- Data for Name: llocs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.llocs (id_lloc, localitat, provincia, capacitat) FROM stdin;
3	Blanes	Girona	10
5	Blanes	Girona	5
6	Blanes	Girona	1001
7	polla	pollas	5120
10	Blanes	Girona	150
11	Blanes	Girona	1
12	Blanes	Girona	150
2	Tordera	Barcelona	200
13	Manresa	Barcelona	500
1	Blanes	Girona	100
14	Girona	Girona	300
\.


--
-- TOC entry 3439 (class 0 OID 16412)
-- Dependencies: 220
-- Data for Name: organizadors; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.organizadors (id_organizador, nom, email, telefon) FROM stdin;
12	Amancio	amancio@sapalomera.cat	900000000
2	Pedro	pedro@sapalomera.cat	600000000
13	Associació Cultural de Manresa	info@acmanresa.cat	938123456
1	Pepe	pepe@sapalomera.cat	666666666
14	Ajuntament de Girona	firanadal@girona.cat	972123456
\.


--
-- TOC entry 3443 (class 0 OID 16483)
-- Dependencies: 224
-- Data for Name: ressenyes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ressenyes (id_ressenyes, id_event, id_usuari, puntuacio, comentari, data_ressenya, nomlloc) FROM stdin;
20	4	3	1	Molt mala experiència, no repetiria, el tracte penós, no recomanable. No poso mitja estrella perquè no puc.	2024-12-05 22:00:00+00	Sapalomera
21	4	2	5	Molt bona experiència	2024-12-05 23:05:12.519827+00	\N
22	11	2	2	Em van llançar un petard molt prop del peu...	2024-12-05 23:07:40.084433+00	\N
\.


--
-- TOC entry 3435 (class 0 OID 16391)
-- Dependencies: 216
-- Data for Name: usuaris; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuaris (id_usuari, nom, email, poble, password, data_registre, data_naixament) FROM stdin;
2	aperez	a.perez@sapalomera.cat	Blanes	$2y$10$NG7MrPQ1kuRZugPzZxItZeQv1HJ59V7Jjjs3YxgTfQ5ueglmgJzFy	2024-11-27 18:28:28.886063+00	2004-10-17
3	mibanez	m.ibanez@sapalomera.cat	Blanes	$2y$10$r/FWYt5ETcm.6X5QOigMzOBtX0vCyX3L/OwFSUcW4a0.VrAZosLTC	2024-11-27 19:16:50.368827+00	2005-09-13
4	pmiro	p.miro@sapalomera.cat	Blanes	$2y$10$DDTXKf/2n359Q7nFKODJTu4nVRAaY5JzFIcBeu29wXu1z5teihoLG	2024-11-27 23:54:15.672465+00	2005-01-01
6	druiz	d.ruiz4@sapalomera.cat	Blanes	$2y$10$cxRZrJnJWjGswxZ43sZ/V.nfZ83FFJV/j7sJ5UN0xYtAWdy6vWRJu	2024-12-05 20:47:04.535942+00	2000-01-01
9	amancio	amancio@sapalomera.cat	Blanes	$2y$10$Eq0GcdkEz8SV51tUIdC0fOjt6bGQvjWZ1iKzv.9ADiSfxAUpDKLcO	2024-12-05 21:12:53.088367+00	2004-10-17
10	palotes	palotes@sapalomera.cat	Blanes	$2y$10$zggTKulILg/M3cBpIG4TSekSW9D5vDdzsgzP5FjFqNzL6SP/ekELa	2024-12-05 21:14:18.769048+00	2004-10-17
11	ProvaFinal	final@sapalomera.cat	Blanes	$2y$10$O811Qo1EMaVS8v3dynB53edg7q9WDQkI8jUvsDnKeV9qma5wJXVje	2024-12-06 14:55:57.876511+00	2024-12-05
\.


--
-- TOC entry 3449 (class 0 OID 0)
-- Dependencies: 221
-- Name: events_id_event_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.events_id_event_seq', 13, true);


--
-- TOC entry 3450 (class 0 OID 0)
-- Dependencies: 217
-- Name: llocs_id_lloc_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.llocs_id_lloc_seq', 14, true);


--
-- TOC entry 3451 (class 0 OID 0)
-- Dependencies: 219
-- Name: organizadors_id_organizador_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.organizadors_id_organizador_seq', 14, true);


--
-- TOC entry 3452 (class 0 OID 0)
-- Dependencies: 223
-- Name: ressenyes_id_ressenyes_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ressenyes_id_ressenyes_seq', 22, true);


--
-- TOC entry 3453 (class 0 OID 0)
-- Dependencies: 215
-- Name: usuaris_id_usuari_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuaris_id_usuari_seq', 11, true);


--
-- TOC entry 3284 (class 2606 OID 16428)
-- Name: events events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_pkey PRIMARY KEY (id_event);


--
-- TOC entry 3278 (class 2606 OID 16410)
-- Name: llocs llocs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.llocs
    ADD CONSTRAINT llocs_pkey PRIMARY KEY (id_lloc);


--
-- TOC entry 3280 (class 2606 OID 16420)
-- Name: organizadors organizadors_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organizadors
    ADD CONSTRAINT organizadors_email_key UNIQUE (email);


--
-- TOC entry 3282 (class 2606 OID 16418)
-- Name: organizadors organizadors_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organizadors
    ADD CONSTRAINT organizadors_pkey PRIMARY KEY (id_organizador);


--
-- TOC entry 3286 (class 2606 OID 16491)
-- Name: ressenyes ressenyes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ressenyes
    ADD CONSTRAINT ressenyes_pkey PRIMARY KEY (id_ressenyes);


--
-- TOC entry 3274 (class 2606 OID 16401)
-- Name: usuaris usuaris_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuaris
    ADD CONSTRAINT usuaris_email_key UNIQUE (email);


--
-- TOC entry 3276 (class 2606 OID 16399)
-- Name: usuaris usuaris_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuaris
    ADD CONSTRAINT usuaris_pkey PRIMARY KEY (id_usuari);


--
-- TOC entry 3287 (class 2606 OID 16429)
-- Name: events events_id_lloc_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_id_lloc_fkey FOREIGN KEY (id_lloc) REFERENCES public.llocs(id_lloc) ON DELETE CASCADE;


--
-- TOC entry 3288 (class 2606 OID 16434)
-- Name: events events_id_organizador_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_id_organizador_fkey FOREIGN KEY (id_organizador) REFERENCES public.organizadors(id_organizador) ON DELETE CASCADE;


--
-- TOC entry 3289 (class 2606 OID 16492)
-- Name: ressenyes ressenyes_id_event_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ressenyes
    ADD CONSTRAINT ressenyes_id_event_fkey FOREIGN KEY (id_event) REFERENCES public.events(id_event) ON DELETE CASCADE;


--
-- TOC entry 3290 (class 2606 OID 16497)
-- Name: ressenyes ressenyes_id_usuari_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ressenyes
    ADD CONSTRAINT ressenyes_id_usuari_fkey FOREIGN KEY (id_usuari) REFERENCES public.usuaris(id_usuari) ON DELETE CASCADE;


-- Completed on 2024-12-06 15:21:50 UTC

--
-- PostgreSQL database dump complete
--

