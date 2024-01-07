--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.25
-- Dumped by pg_dump version 14.10 (Homebrew)

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

--
-- Name: docker; Type: DATABASE; Schema: -; Owner: docker
--

CREATE DATABASE docker WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE = 'en_US.utf8';


ALTER DATABASE docker OWNER TO docker;

\connect docker

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

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: docker
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO docker;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: docker
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

--
-- Name: donates; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.donates (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    fundraising_id bigint NOT NULL,
    amount double precision DEFAULT '0'::double precision NOT NULL,
    hash character varying(255) NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.donates OWNER TO docker;

--
-- Name: donates_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.donates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.donates_id_seq OWNER TO docker;

--
-- Name: donates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.donates_id_seq OWNED BY public.donates.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO docker;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO docker;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: fundraisings; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.fundraisings (
    id bigint NOT NULL,
    key character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    link character varying(255) NOT NULL,
    page character varying(255) NOT NULL,
    description text DEFAULT ''::text NOT NULL,
    spreadsheet_id character varying(255) DEFAULT ''::character varying NOT NULL,
    avatar character varying(255) DEFAULT ''::character varying NOT NULL,
    is_enabled boolean DEFAULT false NOT NULL,
    user_id bigint NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.fundraisings OWNER TO docker;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO docker;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.jobs_id_seq OWNER TO docker;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO docker;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.migrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO docker;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: oauth_access_tokens; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.oauth_access_tokens (
    id character varying(100) NOT NULL,
    user_id bigint,
    client_id bigint NOT NULL,
    name character varying(255),
    scopes text,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_access_tokens OWNER TO docker;

--
-- Name: oauth_auth_codes; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.oauth_auth_codes (
    id character varying(100) NOT NULL,
    user_id bigint NOT NULL,
    client_id bigint NOT NULL,
    scopes text,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_auth_codes OWNER TO docker;

--
-- Name: oauth_clients; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.oauth_clients (
    id bigint NOT NULL,
    user_id bigint,
    name character varying(255) NOT NULL,
    secret character varying(100),
    provider character varying(255),
    redirect text NOT NULL,
    personal_access_client boolean NOT NULL,
    password_client boolean NOT NULL,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_clients OWNER TO docker;

--
-- Name: oauth_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.oauth_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.oauth_clients_id_seq OWNER TO docker;

--
-- Name: oauth_clients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.oauth_clients_id_seq OWNED BY public.oauth_clients.id;


--
-- Name: oauth_personal_access_clients; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.oauth_personal_access_clients (
    id bigint NOT NULL,
    client_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_personal_access_clients OWNER TO docker;

--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.oauth_personal_access_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.oauth_personal_access_clients_id_seq OWNER TO docker;

--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.oauth_personal_access_clients_id_seq OWNED BY public.oauth_personal_access_clients.id;


--
-- Name: oauth_refresh_tokens; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.oauth_refresh_tokens (
    id character varying(100) NOT NULL,
    access_token_id character varying(100) NOT NULL,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_refresh_tokens OWNER TO docker;

--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO docker;

--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO docker;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.personal_access_tokens_id_seq OWNER TO docker;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: user_codes; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.user_codes (
    id bigint NOT NULL,
    hash character varying(255) NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.user_codes OWNER TO docker;

--
-- Name: user_codes_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.user_codes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_codes_id_seq OWNER TO docker;

--
-- Name: user_codes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.user_codes_id_seq OWNED BY public.user_codes.id;


--
-- Name: user_links; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.user_links (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    link character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    icon character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.user_links OWNER TO docker;

--
-- Name: user_links_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.user_links_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_links_id_seq OWNER TO docker;

--
-- Name: user_links_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.user_links_id_seq OWNED BY public.user_links.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    username character varying(255) NOT NULL,
    telegram_id bigint NOT NULL,
    first_name character varying(255) DEFAULT ''::character varying,
    last_name character varying(255) DEFAULT ''::character varying,
    avatar character varying(255) DEFAULT ''::character varying,
    is_premium boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO docker;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO docker;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: volunteers_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.volunteers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.volunteers_id_seq OWNER TO docker;

--
-- Name: volunteers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.volunteers_id_seq OWNED BY public.fundraisings.id;


--
-- Name: donates id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.donates ALTER COLUMN id SET DEFAULT nextval('public.donates_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: fundraisings id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.fundraisings ALTER COLUMN id SET DEFAULT nextval('public.volunteers_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: oauth_clients id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_clients ALTER COLUMN id SET DEFAULT nextval('public.oauth_clients_id_seq'::regclass);


--
-- Name: oauth_personal_access_clients id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_personal_access_clients ALTER COLUMN id SET DEFAULT nextval('public.oauth_personal_access_clients_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: user_codes id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_codes ALTER COLUMN id SET DEFAULT nextval('public.user_codes_id_seq'::regclass);


--
-- Name: user_links id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_links ALTER COLUMN id SET DEFAULT nextval('public.user_links_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: donates; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.donates (id, user_id, fundraising_id, amount, hash, deleted_at, created_at, updated_at) FROM stdin;
260	54	1	50	65919790c820c5.07007887	\N	2023-12-31 16:32:38	2024-01-05 00:56:29
261	9	1	4	65913c736b9117.69245343	\N	2023-12-31 10:03:47	2024-01-05 00:56:29
262	9	1	2	6580514c88e0f6.12269704	\N	2023-12-18 14:04:09	2024-01-05 00:56:29
263	1	1	0.0200000000000000004	657af1e861d3c1.84149064	\N	2023-12-17 01:53:21	2024-01-05 00:56:29
264	60	1	333	657adc870b2f75.10531153	\N	2023-12-14 12:16:45	2024-01-05 00:56:29
265	60	1	333	657adb85979ea3.93195123	\N	2023-12-14 12:16:00	2024-01-05 00:56:29
266	55	1	100	6574c508a91673.36906471	\N	2023-12-14 10:45:08	2024-01-05 00:56:29
267	9	1	100	6574a417e62873.30042658	\N	2023-12-14 10:43:32	2024-01-05 00:56:29
268	11	1	2.04999999999999982	6562ec0416602	\N	2023-12-09 17:30:18	2024-01-05 00:56:29
269	12	1	50	655b46ece844d	\N	2023-11-28 21:02:18	2024-01-05 00:56:29
270	3	1	10	6559734016d1c	\N	2023-11-26 06:59:29	2024-01-05 00:56:29
271	54	1	111	65919790c820c5.07007887	\N	2023-11-19 02:31:02	2024-01-05 00:56:29
272	9	1	11	65913c736b9117.69245343	\N	2023-11-17 09:15:20	2024-01-05 00:56:29
280	9	3	4	65913c55583702.62335276	\N	2023-12-31 10:03:23	2024-01-05 00:56:35
281	65	3	10	6580d16722ffd8.18994109	\N	2023-12-18 23:13:20	2024-01-05 00:56:35
282	9	3	2	6580513800c417.29106445	\N	2023-12-18 14:03:49	2024-01-05 00:56:36
283	9	3	2	6574a3d3b8fc50.87153928	\N	2023-12-09 17:29:06	2024-01-05 00:56:36
284	9	3	2	65731c7d7ad2b7.78935135	\N	2023-12-08 13:39:24	2024-01-05 00:56:36
285	9	3	3	656eeeaba62861.93497869	\N	2023-12-05 09:34:54	2024-01-05 00:56:36
286	41	3	200	6568ffaea1ee83.21826949	\N	2023-11-30 21:35:15	2024-01-05 00:56:36
287	9	3	1	656469e28a0296.05032767	\N	2023-11-27 10:05:35	2024-01-05 00:56:36
288	28	3	100	6563b3f889000	\N	2023-11-26 21:17:33	2024-01-05 00:56:36
289	12	3	33	655b473f08897	\N	2023-11-20 20:52:27	2024-01-05 00:56:36
290	9	3	10	655af5dcb4b28	\N	2023-11-20 11:47:32	2024-01-05 00:56:36
291	9	3	5	655af5dcb4b28	\N	2023-11-20 06:01:56	2024-01-05 00:56:36
292	3	3	5	655972dce2257	\N	2023-11-20 06:01:10	2024-01-05 00:56:36
307	1	5	111	65675ba2628981.23519114	\N	2023-11-29 15:41:36	2024-01-05 00:56:41
308	18	5	555	656741b3b7f408.16538817	\N	2023-11-29 13:52:08	2024-01-05 00:56:41
309	1	5	111	656607a0427e87.53837856	\N	2023-11-28 15:31:03	2024-01-05 00:56:42
310	1	5	111	656475241ed540.77174408	\N	2023-11-27 10:53:50	2024-01-05 00:56:42
311	9	5	1	6564698ba739b8.91237837	\N	2023-11-27 10:04:13	2024-01-05 00:56:42
312	29	5	17	6564509e9c34c	\N	2023-11-27 08:20:32	2024-01-05 00:56:42
313	3	5	111	6563f5b5e4293	\N	2023-11-27 01:50:49	2024-01-05 00:56:42
314	1	5	111	6563a238e3637	\N	2023-11-26 19:54:32	2024-01-05 00:56:42
315	1	5	111	6562985b978d5	\N	2023-11-26 00:59:24	2024-01-05 00:56:42
316	9	5	1	65621462b74a8	\N	2023-11-25 15:36:19	2024-01-05 00:56:42
317	13	5	50	6561e3426e9fb	\N	2023-11-25 12:07:57	2024-01-05 00:56:42
318	7	5	50	6560f85fb31f9	\N	2023-11-24 19:25:35	2024-01-05 00:56:42
319	9	5	1	65609ed62f10c	\N	2023-11-24 13:02:07	2024-01-05 00:56:42
320	25	5	10	655fb41e1be0e	\N	2023-11-23 20:17:30	2024-01-05 00:56:42
321	25	5	10	655fb41e1be0e	\N	2023-11-23 20:15:46	2024-01-05 00:56:42
322	7	5	50	655fa21055daf	\N	2023-11-23 19:04:19	2024-01-05 00:56:42
323	1	5	1	655e63ff9a190	\N	2023-11-22 20:27:39	2024-01-05 00:56:42
324	1	5	111	655e18bb0bdf0	\N	2023-11-22 15:06:07	2024-01-05 00:56:42
325	9	5	3	655d9c1bb94b9	\N	2023-11-22 06:14:22	2024-01-05 00:56:42
326	1	5	111	655d49cc1ad16	\N	2023-11-22 00:22:46	2024-01-05 00:56:42
327	1	5	111	655d4984de01f	\N	2023-11-22 00:21:07	2024-01-05 00:56:42
328	1	5	111	655d49124f7c5	\N	2023-11-22 00:19:34	2024-01-05 00:56:42
329	13	5	50	655d129ad5a03	\N	2023-11-21 20:28:01	2024-01-05 00:56:42
330	19	5	200	655d0d9f03343	\N	2023-11-21 20:06:50	2024-01-05 00:56:42
331	7	5	50	655d0d532599e	\N	2023-11-21 20:06:12	2024-01-05 00:56:42
332	20	5	10	655d0d6498b54	\N	2023-11-21 20:05:33	2024-01-05 00:56:43
333	1	5	111	655cf6db8c2c3	\N	2023-11-21 18:29:08	2024-01-05 00:56:43
334	1	5	111	655cb65c0e591	\N	2023-11-21 13:54:15	2024-01-05 00:56:43
335	3	5	111	655c91ebeb1bb	\N	2023-11-21 11:18:36	2024-01-05 00:56:43
336	16	5	1	655c8d722efc0	\N	2023-11-21 10:59:22	2024-01-05 00:56:43
337	7	5	50	655c890c43751	\N	2023-11-21 10:41:45	2024-01-05 00:56:43
338	9	5	2	655c830e11101	\N	2023-11-21 10:15:19	2024-01-05 00:56:43
339	2	5	33	655bc6b1898e6	\N	2023-11-20 20:51:44	2024-01-05 00:56:43
340	1	5	111	655bc5e8be555	\N	2023-11-20 20:48:28	2024-01-05 00:56:43
341	1	5	111	655b7e1996556	\N	2023-11-20 15:42:04	2024-01-05 00:56:43
342	14	5	1	655b655ec5ef5	\N	2023-11-20 13:58:10	2024-01-05 00:56:43
343	13	5	75	655b636a634f4	\N	2023-11-20 13:47:56	2024-01-05 00:56:43
344	7	5	50	655b50f6e9186	\N	2023-11-20 12:29:46	2024-01-05 00:56:43
345	12	5	10	655b46c0d702f	\N	2023-11-20 11:45:35	2024-01-05 00:56:43
346	12	5	100	655b426686de2	\N	2023-11-20 11:28:35	2024-01-05 00:56:43
347	12	5	100	655b42c9e61e0	\N	2023-11-20 11:27:51	2024-01-05 00:56:43
348	10	5	5.08000000000000007	655a92da1add6	\N	2023-11-19 22:57:54	2024-01-05 00:56:43
349	10	5	1	655a92b942dbe	\N	2023-11-19 22:57:21	2024-01-05 00:56:43
350	10	5	1	655a925a7e6d9	\N	2023-11-19 22:55:45	2024-01-05 00:56:43
351	10	5	1	655a9233b4055	\N	2023-11-19 22:55:16	2024-01-05 00:56:43
352	10	5	1	655a91f66ded0	\N	2023-11-19 22:54:24	2024-01-05 00:56:44
353	1	5	111	655a8e77b9dfe	\N	2023-11-19 22:39:33	2024-01-05 00:56:44
354	10	5	5	655a91f66ded0	\N	2023-11-19 21:03:05	2024-01-05 00:56:44
368	54	7	50	659197ef0491a7.14413338	\N	2023-12-31 16:34:03	2024-01-05 00:56:52
369	9	7	5	65913cd4687682.19939953	\N	2023-12-31 10:05:23	2024-01-05 00:56:52
480	1	14	111	iF8jy	\N	2024-01-05 10:30:19	2024-01-05 16:46:04
273	54	2	50	6591981abe1730.65423857	\N	2023-12-31 16:34:51	2024-01-05 00:56:32
274	9	2	0.0100000000000000002	6574a44f601fc7.08264179	\N	2023-12-19 01:55:52	2024-01-05 00:56:32
275	12	2	3.29999999999999982	655b471893201	\N	2023-12-09 17:31:14	2024-01-05 00:56:32
276	7	2	0.0100000000000000002	655a28b56f3f4	\N	2023-12-01 16:16:48	2024-01-05 00:56:32
277	3	2	10	655973c402ce3	\N	2023-11-20 11:46:59	2024-01-05 00:56:32
278	54	2	50	6591981abe1730.65423857	\N	2023-11-19 15:27:00	2024-01-05 00:56:32
279	9	2	111	6574a44f601fc7.08264179	\N	2023-11-19 02:33:09	2024-01-05 00:56:32
293	54	4	50	659197c1c0c2a7.43427672	\N	2023-12-31 16:33:20	2024-01-05 00:56:38
294	27	4	200	65915b44064fd2.75057091	\N	2023-12-31 12:18:22	2024-01-05 00:56:38
295	9	4	5	65913cbb15e617.89425255	\N	2023-12-31 10:04:57	2024-01-05 00:56:38
296	9	4	0.0200000000000000004	65805161904a17.01225995	\N	2023-12-19 01:56:52	2024-01-05 00:56:39
297	9	4	0.0100000000000000002	6578a292a2eca1.38660369	\N	2023-12-19 01:31:01	2024-01-05 00:56:39
298	13	4	2	6574a51a7ccd79.24636786	\N	2023-12-18 14:05:41	2024-01-05 00:56:39
299	9	4	1	6574a43260e664.37407912	\N	2023-12-12 18:12:58	2024-01-05 00:56:39
300	9	4	69	65731cd5c10e68.28834401	\N	2023-12-09 17:34:46	2024-01-05 00:56:39
301	9	4	2.14000000000000012	65646a27898262.98398604	\N	2023-12-09 17:30:47	2024-01-05 00:56:39
302	1	4	2	655bdecb4e015	\N	2023-12-08 13:40:56	2024-01-05 00:56:39
303	3	4	0.0100000000000000002	655973f92c934	\N	2023-12-01 16:16:49	2024-01-05 00:56:39
304	54	4	1	659197c1c0c2a7.43427672	\N	2023-11-27 10:06:45	2024-01-05 00:56:39
305	27	4	111	65915b44064fd2.75057091	\N	2023-11-20 22:34:20	2024-01-05 00:56:39
306	9	4	111	65913cbb15e617.89425255	\N	2023-11-19 02:33:50	2024-01-05 00:56:39
355	60	6	100	657adde7cde6e7.76336773	\N	2023-12-14 10:51:33	2024-01-05 00:56:48
356	9	6	2	6579a326b0bcf6.75724919	\N	2023-12-13 12:27:38	2024-01-05 00:56:48
357	9	6	1	6578a26b397ab9.32679530	\N	2023-12-12 18:12:18	2024-01-05 00:56:48
358	9	6	1	6578a2521690d2.76336137	\N	2023-12-12 18:11:46	2024-01-05 00:56:48
359	9	6	2	6574a402199790.04926091	\N	2023-12-09 17:29:52	2024-01-05 00:56:48
360	9	6	2	65731cb084f0a2.51919913	\N	2023-12-08 13:40:14	2024-01-05 00:56:48
361	9	6	1	656eeecbdf52c8.71578152	\N	2023-12-05 09:35:23	2024-01-05 00:56:48
362	41	6	100	6569005daa0948.11000368	\N	2023-11-30 21:37:18	2024-01-05 00:56:48
363	9	6	1	65621435bf114	\N	2023-11-25 15:35:54	2024-01-05 00:56:48
364	13	6	101	6561e648f1fb6	\N	2023-11-25 12:19:43	2024-01-05 00:56:48
365	9	6	1	65609f222c5f9	\N	2023-11-24 13:03:54	2024-01-05 00:56:48
366	1	6	612.259999999999991	655cb5601b554	\N	2023-11-21 13:50:35	2024-01-05 00:56:48
367	9	6	4	6574a402199790.04926091	\N	2023-11-03 18:51:44	2024-01-05 00:56:49
370	63	7	0.0100000000000000002	657dc35d99ab25.81605781	\N	2023-12-23 02:25:36	2024-01-05 00:56:52
371	9	7	50	6574a47ad90a58.00155199	\N	2023-12-16 15:34:41	2024-01-05 00:56:52
372	13	7	50	657202e1b77437.83149167	\N	2023-12-09 18:43:45	2024-01-05 00:56:52
373	1	7	3	655e6dcf09a41	\N	2023-12-09 17:31:55	2024-01-05 00:56:52
374	54	7	55	659197ef0491a7.14413338	\N	2023-12-07 17:38:02	2024-01-05 00:56:52
375	9	7	111	65913cd4687682.19939953	\N	2023-11-22 21:09:09	2024-01-05 00:56:53
376	1	8	111	656b0e8a9d67d0.89225533	\N	2023-12-02 11:02:00	2024-01-05 00:56:56
377	1	8	111	6569a182b18c63.34813729	\N	2023-12-01 09:04:38	2024-01-05 00:56:56
378	1	8	111	6568ddf2ed10e4.83189405	\N	2023-11-30 19:10:23	2024-01-05 00:56:56
379	24	8	20	6567a04a40c760.76121848	\N	2023-11-29 20:34:43	2024-01-05 00:56:56
380	1	8	111	65679eaea90320.57247586	\N	2023-11-29 20:28:00	2024-01-05 00:56:56
381	7	8	20.6000000000000014	65679e95b66cb7.26371958	\N	2023-11-29 20:27:29	2024-01-05 00:56:56
382	24	8	20	65679e2de090c7.67654695	\N	2023-11-29 20:25:48	2024-01-05 00:56:56
383	40	8	70	65679c0c38ef77.33782785	\N	2023-11-29 20:17:20	2024-01-05 00:56:56
384	19	8	200	65679b84c1b052.19293789	\N	2023-11-29 20:09:22	2024-01-05 00:56:56
385	7	8	50	656790fa787869.33743920	\N	2023-11-29 19:30:33	2024-01-05 00:56:56
386	13	8	100	65679117df73f5.04285875	\N	2023-11-29 19:30:07	2024-01-05 00:56:56
387	39	8	200	65676e6e8fb8d1.64650806	\N	2023-11-29 17:02:19	2024-01-05 00:56:56
388	1	8	111	65675b6ab59a94.95233172	\N	2023-11-29 15:40:56	2024-01-05 00:56:56
389	18	8	100	656741b3b7f408.16538817	\N	2023-11-29 13:51:47	2024-01-05 00:56:56
390	37	8	50	6566562de5fc83.12843621	\N	2023-11-28 21:07:27	2024-01-05 00:56:57
391	16	8	1000	65661aea9fb245.89740899	\N	2023-11-28 16:53:25	2024-01-05 00:56:57
392	1	8	111	6566075ed21253.18109544	\N	2023-11-28 15:29:53	2024-01-05 00:56:57
393	1	8	111	656474e56923a6.39450835	\N	2023-11-27 10:53:01	2024-01-05 00:56:57
394	9	8	1	656469a9d58b29.48003005	\N	2023-11-27 10:04:39	2024-01-05 00:56:57
395	30	8	100	65645c113630f7.52679678	\N	2023-11-27 09:07:19	2024-01-05 00:56:57
396	3	8	111	6563f602baa48	\N	2023-11-27 01:51:20	2024-01-05 00:56:57
397	1	8	111	6563a2813f771	\N	2023-11-26 19:55:10	2024-01-05 00:56:57
398	1	8	111	656298ad8ce26	\N	2023-11-26 01:00:50	2024-01-05 00:56:57
399	13	8	50	6561e693bd8d9	\N	2023-11-25 12:20:49	2024-01-05 00:56:57
400	9	8	1	65609ef76f69a	\N	2023-11-24 13:03:21	2024-01-05 00:56:57
401	26	8	10	655fb415c9eeb	\N	2023-11-23 20:21:20	2024-01-05 00:56:57
402	1	8	111	655f88f8e83ae	\N	2023-11-23 17:17:14	2024-01-05 00:56:57
403	9	8	3	655f3f821f334	\N	2023-11-23 12:03:56	2024-01-05 00:56:57
404	22	8	20	655e6b2376038	\N	2023-11-22 20:57:35	2024-01-05 00:56:57
405	21	8	50	655e63a398b99	\N	2023-11-22 20:26:21	2024-01-05 00:56:57
406	1	8	1111	655e63bf7149f	\N	2023-11-22 20:26:16	2024-01-05 00:56:57
407	1	10	100	658a0e43643388.45893189	\N	2023-12-25 23:59:54	2024-01-05 00:57:05
408	41	10	30	65872c60eaf9a5.03174244	\N	2023-12-23 22:49:45	2024-01-05 00:57:05
137	41	8	0	65679c89384c28.17976775	2023-12-03 16:59:44	2023-12-03 14:00:58	2023-12-03 16:59:44
409	1	10	1000	6581ab5e6e64f1.51649806	\N	2023-12-19 23:19:36	2024-01-05 00:57:06
410	9	10	500	65805120bcbac6.99979616	\N	2023-12-18 21:07:54	2024-01-05 00:57:06
411	1	10	200	657e252e429c60.78022598	\N	2023-12-17 15:24:33	2024-01-05 00:57:06
412	27	10	10	657a44ac2652e7.41623747	\N	2023-12-15 10:59:46	2024-01-05 00:57:07
413	9	10	120	6579a2f6144f18.36029873	\N	2023-12-14 06:49:52	2024-01-05 00:57:07
414	9	10	20	6578a216d52649.90831998	\N	2023-12-13 21:54:16	2024-01-05 00:57:07
415	1	10	300	65785ab67ec5b4.85252037	\N	2023-12-13 19:10:21	2024-01-05 00:57:07
416	9	10	10	6574a3b76e76b1.48927411	\N	2023-12-11 07:52:25	2024-01-05 00:57:07
417	9	10	100	65731c55974133.65680757	\N	2023-12-10 13:23:33	2024-01-05 00:57:07
418	41	10	450	6570a3fed38900.26274525	\N	2023-12-07 09:20:05	2024-01-05 00:57:08
419	31	10	100	65702b4edf73b8.03043050	\N	2023-12-06 14:35:57	2024-01-05 00:57:08
420	9	10	300	656eee75b0bf59.41105200	\N	2023-12-06 11:34:55	2024-01-05 00:57:08
421	7	10	150	656e43ff3bf6b6.52495152	\N	2023-12-06 11:04:20	2024-01-05 00:57:08
422	1	10	5	65675bc95af215.40447781	\N	2023-12-04 06:25:53	2024-01-05 00:57:09
423	7	10	111	6566bcc5cb1a40.83789456	\N	2023-11-29 15:42:23	2024-01-05 00:57:09
424	13	10	100	65665588f1eff4.00130112	\N	2023-11-29 11:09:24	2024-01-05 00:57:09
425	1	10	150	6566546b38f513.55055746	\N	2023-11-29 10:06:10	2024-01-05 00:57:09
426	1	10	500	6565c68c2ae7f3.66962952	\N	2023-11-28 20:32:38	2024-01-05 00:57:09
427	32	10	21	6565be9137d015.23642914	\N	2023-11-28 20:17:42	2024-01-05 00:57:09
428	59	11	50	6579b66e88d1e2.55546917	\N	2023-12-13 13:51:00	2024-01-05 00:57:13
429	59	11	100	6579b67ec1d602.67398614	\N	2023-12-13 13:50:35	2024-01-05 00:57:13
430	9	11	2	6579a31270df37.82181918	\N	2023-12-13 12:27:10	2024-01-05 00:57:13
431	58	11	20	6578c2bfe129a9.30039376	\N	2023-12-12 20:31:10	2024-01-05 00:57:13
432	9	11	2	6578a23aa49de0.58934826	\N	2023-12-12 18:11:20	2024-01-05 00:57:13
433	1	11	111	65785a801fdbc6.25824687	\N	2023-12-12 13:05:22	2024-01-05 00:57:13
434	45	11	50	657707ec461f20.84925783	\N	2023-12-11 13:00:52	2024-01-05 00:57:14
435	56	11	50	6574e52b95ad22.52885389	\N	2023-12-09 22:08:54	2024-01-05 00:57:14
436	9	11	2	6574a3eb24fa43.95504034	\N	2023-12-09 17:29:29	2024-01-05 00:57:14
437	47	11	200	657390cae1cb88.57861440	\N	2023-12-08 21:56:12	2024-01-05 00:57:14
438	9	11	2	65731c97245c38.09051992	\N	2023-12-08 13:39:52	2024-01-05 00:57:14
439	46	11	500	6572aea0df45d5.37970667	\N	2023-12-08 05:51:21	2024-01-05 00:57:14
440	42	11	1000	65710167d21897.37267599	\N	2023-12-06 22:21:38	2024-01-05 00:57:14
441	18	11	150	6570aef62f5149.05967534	\N	2023-12-06 16:29:09	2024-01-05 00:57:14
442	13	11	50	6570a6fd7a9fc9.49682303	\N	2023-12-06 15:53:33	2024-01-05 00:57:14
443	13	11	50	6570a6da5facf7.28542453	\N	2023-12-06 15:53:04	2024-01-05 00:57:14
444	41	11	100	6570a388cacc05.68727855	\N	2023-12-06 15:38:59	2024-01-05 00:57:14
445	1	11	1111	6570a2884ec7d2.99202585	\N	2023-12-06 15:34:48	2024-01-05 00:57:14
446	50	12	69	6574516ec8f4e1.00105415	\N	2023-12-09 11:40:31	2024-01-05 00:57:16
447	1	12	1111	657382286c17f7.49557034	\N	2023-12-08 20:53:49	2024-01-05 00:57:16
448	78	13	50	65928372c4d294.33312715	\N	2024-01-01 09:18:00	2024-01-05 00:57:19
449	27	13	50	65915d4245c454.18157587	\N	2023-12-31 12:26:00	2024-01-05 00:57:19
450	27	13	50	65915722662ed0.05583396	\N	2023-12-31 11:58:00	2024-01-05 00:57:19
451	9	13	4	65913c8be31223.12095400	\N	2023-12-31 10:04:00	2024-01-05 00:57:19
452	74	13	50	65909c9b3d8349.82248043	\N	2023-12-30 22:41:00	2024-01-05 00:57:19
453	74	13	50	65909bcf899322.68607841	\N	2023-12-30 22:38:00	2024-01-05 00:57:19
454	1	13	333	658a0e18054b97.83134108	\N	2023-12-25 23:20:15	2024-01-05 00:57:19
455	42	13	500	658225488d8d26.58805594	\N	2023-12-19 23:20:58	2024-01-05 00:57:19
456	67	13	500	6581e75d8a0e18.82527552	\N	2023-12-19 18:57:00	2024-01-05 00:57:19
457	7	13	50	6581e18f415e48.48580820	\N	2023-12-19 18:32:08	2024-01-05 00:57:19
458	27	13	10	6581b1ed41a298.97032823	\N	2023-12-19 15:09:30	2024-01-05 00:57:19
459	1	13	333	6581aaf774d1f9.93065386	\N	2023-12-19 14:39:32	2024-01-05 00:57:19
460	58	13	50	6578c2ac0a5258.93915692	\N	2023-12-19 12:35:23	2024-01-05 00:57:19
461	13	13	50	65818cd80cca17.54013753	\N	2023-12-19 12:30:28	2024-01-05 00:57:19
462	1	13	111	657e255fe169b9.31429984	\N	2023-12-16 22:32:28	2024-01-05 00:57:19
463	1	13	111	65785a4886c144.26256277	\N	2023-12-12 13:04:31	2024-01-05 00:57:19
464	54	13	100	6575c31d2e8852.97781365	\N	2023-12-10 13:55:28	2024-01-05 00:57:19
465	54	14	100	6591971b879f66.33073603	\N	2023-12-31 16:31:04	2024-01-05 00:57:23
466	77	14	50	659157edc7d808.18366889	\N	2023-12-31 12:01:22	2024-01-05 00:57:23
467	69	14	111	65915753a845b4.69037860	\N	2023-12-31 11:59:19	2024-01-05 00:57:23
468	9	14	4	65913ca5786068.13348904	\N	2023-12-31 10:04:33	2024-01-05 00:57:23
469	76	14	100	65912f4bca5657.20974991	\N	2023-12-31 09:08:11	2024-01-05 00:57:23
470	74	14	50	65909b4e49d776.26649494	\N	2023-12-30 22:36:38	2024-01-05 00:57:23
471	73	14	50	65907afe7ee304.97018247	\N	2023-12-30 20:19:46	2024-01-05 00:57:23
472	41	14	50	6590796e5dfee8.90148392	\N	2023-12-30 20:12:02	2024-01-05 00:57:23
473	73	14	50	659078ff750454.42788242	\N	2023-12-30 20:10:17	2024-01-05 00:57:23
474	1	14	333	658a0d1417afb6.81985908	\N	2023-12-25 23:15:49	2024-01-05 00:57:24
475	42	14	500	6582269b297437.65426428	\N	2023-12-19 23:26:41	2024-01-05 00:57:24
476	1	14	333	6581ab7ee6e271.31328376	\N	2023-12-19 14:41:19	2024-01-05 00:57:24
477	65	14	1	6580d16722ffd8.18994109	\N	2023-12-18 23:11:51	2024-01-05 00:57:24
478	1	14	111	657e259052e428.75093549	\N	2023-12-16 22:33:08	2024-01-05 00:57:24
479	81	4	100	65972e790fd713.86611998	\N	2024-01-04 22:18:10	2024-01-05 01:28:02
481	18	14	555	DlKVG	\N	2024-01-05 07:03:07	2024-01-05 16:46:05
482	42	14	1000	65958b9711feb8.52291853	\N	2024-01-03 16:31:31	2024-01-05 16:46:05
483	41	14	100	659587575d6d71.68532446	\N	2024-01-03 16:13:58	2024-01-05 16:46:05
484	13	14	100	6595877ff23a28.49215978	\N	2024-01-03 16:13:10	2024-01-05 16:46:05
485	78	14	50	659282a7dc2758.82742739	\N	2024-01-01 09:15:53	2024-01-05 16:46:06
486	1	18	111	iF8jy	\N	2024-01-05 21:15:00	2024-01-06 16:31:16
487	81	18	100	72WXh	\N	2024-01-05 18:50:00	2024-01-06 16:31:16
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: fundraisings; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.fundraisings (id, key, name, link, page, description, spreadsheet_id, avatar, is_enabled, user_id, deleted_at, created_at, updated_at) FROM stdin;
5	glits04_1	Збір на зв'язок	https://send.monobank.ua/jar/6nfMdq4Wph	https://x.com/Gilts04/status/1730201985914184121?s=20	<p>Збор коштів на мережеве обладнання для роти звʼязку 80 окремого батальйону управління КМП та тепловізійного монокуляру взводу охорони того ж батальйону. Підрозділ займається забезпеченням звʼязку в Миколаївській та Херсонській областях.<br><br><strong>Розіграш павербанку BASEUS 10000mAh 22.5w 1 донат від 1грн, 1 код - 1 квиток.</strong></p>\n<p><strong>Переможиця - <a href="../../u/alexsinchukova">@alexsinchukova</a></strong></p>\n<p>Збір закрито на суммі в 26727,55 грн. у зв'язку з закритям потреб благодійником</p>\n<p>Кошти зі збору було витрачено на</p>\n<ul>\n<li>Вантажний акумулятор WESTA 6CT-140 Аз standard Pretty Powerful WST140/WPP140 2 штуки - 10770 грн.</li>\n<li>\n<p class="product__title-left product__title-collapsed ng-star-inserted">Автомобільний акумулятор Bosch 74Ah Єв (-/+) S4008 (680EN) (0 092 S40 080) 1 штука - 4103 грн.</p>\n</li>\n<li>\n<p class="product__title-left product__title-collapsed ng-star-inserted">&nbsp;Маршрутизатор MikroTik hAP ax2 (C52iG-5HaxD2HaxD-TC) 2 штуки - 6897 грн.</p>\n</li>\n<li>Залишок коштів на наступний збір - 4 957,55 грн.</li>\n</ul>	1l47ghNanVRl5Q4lIxhFsA13iMNXXcKxbCdD_OWNnGvs	/images/banners/glits04_1.png	f	6	\N	2023-11-26 17:08:46	2023-11-30 16:47:38
7	rusoriz	Русоріз пана Стерненко	https://send.monobank.ua/jar/9ekqtYDMca	https://twitter.com/sternenko	🫡 Спільнота пана Стерненко допомогли війську на понад 480 (!!!) мільйонів гривень!<br>\n<br>\nСаме такий є проміжний звіт за період з 1 квітня 2022 по 12 серпня 2023. <br>\nНасправді сума і кількість переданого майна вже більша. <br>\n<br>\nНа ці кошти ми закупили у армію:<br>\n- понад 6000 FPV-дронів<br>\n- майже 1000 інших БПЛА<br>\n- 110 тепловізорів<br>\n- 107 ПНБ<br>\n- 225 одиниць транспорту <br>\nТа багато-багато іншого!<br>\nЗокрема, 1 надводний дрон та вклали кошти у дрони дальнього радіусу дії😉<br>\n<br>\nТакож до цієї справи долучився і я, сума моїх донатів склала 2 658 984 грн. <br>\nАле ваш внесок — величезний!<br>\nВи дуже істотно допомогли фронту. <br>\n<br>\nДякую вам!<br>\n<br>\nВолонтерські картки: <br>\n4441114454997899 моно<br>\n5168745030910761 приват<br>	1UbzlN19o1ahNK-rmnDwB5pPkR1E-O5YiGy6MwMyyn38	/images/banners/rusoriz.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 17:39:50
1	savelife	Повернись живим	https://send.monobank.ua/jar/91w3asqDZt	https://savelife.in.ua/en/	«Повернись живим» — це благодійний фонд компетентної допомоги армії, а також громадська організація, яка займається аналітикою у секторі безпеки та оборони, реалізує проєкти з реабілітації ветеранів через спорт.	1YqwMthW7y5SXM059AuAogkmhDg7BVMextxPT3IuB9_s	/images/banners/savelife.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 17:54:03
2	prytulafoundation	Фонд Сергія Притули	https://send.monobank.ua/jar/4aqbQf23WR	https://prytulafoundation.org/	Благодійний фонд Сергія Притули опікується посиленням Сил Оборони України, а також допомогою цивільному населенню, яке постраждало від російської агресії.	1dKiA7w69uv5FaawrSEXg04eiQW1FafDr8vNTGBTKAok	/images/banners/prytulafoundation.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 17:54:06
3	hospitallers	Медичний батальйон "Госпітальєри"	https://send.monobank.ua/jar/4Mtimtorvu	https://www.hospitallers.life	“Госпітальєри”— добровольча організація парамедиків. Була заснована Яною Зінкевич на початку бойових дій в Україні у 2014 році. Тоді Росія анексувала Крим і розпочала бойові дії на сході країни.	1ZSPaWAdm4aW-ZBwrzdk5u-vQwSda_wigj6bVjrDelOk	/images/banners/hospitallers.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 17:54:10
4	letsseethevictory	Фонд "Побачимо Перемогу"	https://send.monobank.ua/jar/4TmC32mY17	https://thevictory.org.ua	<h2>Наша місія</h2>\n<p>Місія нашого Благодійного Фонду полягає в тому, щоб допомагати людям, які втратили зір під час війни. На жаль, пересадка очей неможлива, тому життя людей після такої травми змінюється радикально.</p>\n<p>Фонд займається пошуком необхідних програм реабілітації та сучасних технологій відновлення зору в Україні та за кордоном, а також впровадженням в Україні існуючих методик та технологій по відновленню зору та реабілітації незрячих.</p>\n<h3>Медична допомога</h3>\n<p>Забезпечуємо постраждалим фінансування для якісного лікування, реабілітації та придбання медичних засобів, полегшуючи повсякденне функціонування.</p>\n<h3>Психологічна підтримка</h3>\n<p>Надаємо професійну допомогу постраждалим у вирішенні емоційних труднощів, стресу та тривоги, що виникають внаслідок втрати зору.</p>\n<h3>Соціальна підтримка</h3>\n<p>Допомагаємо постраждалим інтегруватися в суспільство, забезпечуючи інформацію, освіту, професійну підготовку та підтримку в зайнятості.</p>\n<h3>Розвиток та адаптація технологій</h3>\n<p>Сприяємо впровадженню інноваційних технологій, які полегшують повсякденне життя, роботу та навчання людей з втратою зору.</p>\n<h3>Соціальне обговорення та освіта</h3>\n<p>Підвищуємо обізнаність суспільства про проблеми людей з втратою зору, просуваємо толерантність та боремося зі стигмою та дискримінацією.</p>\n<h3>Юридична допомога</h3>\n<p>Забезпечуємо людям з втратою зору юридичну підтримку та консультування, спрямовані на захист їх прав та інтересів. Допомагаємо з отриманням інвалідності, соціальними виплатами та доступом до потрібних послуг і пільг.</p>	1lB-CZLWPg--o5YMdNbvuokL1Gmv_YzzEwqCa17JZfpA	https://donater.com.ua/images/banners/admin/Logo_128_newcolors-01.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 18:27:33
6	setnemo_twitter_subscribe	Збір Госпам від setnemo	https://send.monobank.ua/jar/3irfquacv1	https://twitter.com/setnemo/status/1721256589582049664	<p>Збір госпам в адміна. Донатить в банку в за кожного підписника в тві. Дедлайн - 25 грудня 2023. За донат в банку від 100грн - шанс виграти нічник Місяць. Всього буде розиграно 2 нічника, один за реєстрацію по посиланню після донату, другий по донатам з кодами з цього сайту.<br><br><strong>Розіграш 2 x Moon Lamp 20sm. 100грн донату - 1 квиток</strong></p>	1y7TY9Cuo48kvXA4V4WNKouR8UroF7E514Oc8aKifylI	https://donater.com.ua/images/banners/admin/Possters_All_Type-01.png	f	3	\N	2023-11-26 17:08:46	2023-12-14 20:52:44
11	zbir_na_fpv_dlya_52-go_osb	ЗБІР НА FPV ДЛЯ 52-ГО ОСБ	https://send.monobank.ua/jar/5qL3tGuQ5t	https://x.com/a_vodianko/status/1732424298143572282?s=20	<p dir="ltr">52-му Окремому Стрілецькому Батальйону потрібні нічні дрони FPV, щоб ефективно робити смерть ворогам на Авдіївському напрямку. Збираємо на п'ять дронів, ціль 90.000 грн</p>\n<p dir="ltr">Номер карти моно: 5375411209848097<br>PayPal: a.vodianko@gmail.com</p>\n<p dir="ltr">Розіграш Kalimba Thumb Piano 17 Keys Mahogany Wood Portable Finger Piano Combinations Gifts for Kids</p>\n<p dir="ltr"><strong>1 квиток - 50 грн з кодом, </strong><strong>2 квитки - 100 грн з кодом, і тд</strong></p>\n<p>&nbsp;</p>	1E0MbrI0VhpvGzluGirSYvVqzzdA1kPwH38vH_-0v-3U	https://donater.com.ua/images/banners/GoldSquealing/ok LOTERIA POST 1x1 UA vol.2 80k_ok.png	f	27	\N	2023-12-03 17:32:09	2023-12-14 02:03:21
8	52_fpv_01	Зб*р на 5 FPV для 52-го ОСБ 🐝	https://send.monobank.ua/jar/5qL3tGuQ5t	https://twitter.com/a_vodianko/status/1725862229768016335	<p>Зб*р на 5 FPV для 52-го ОСБ 🐝<br>Ситуація на Авдіївському напрямку складна. Ворог не економить FPV, запускає цілі рої др*нів по нашій піхоті. 5 FPV це мало, але зберемо більше-купимо більше!<br>Номер карти моно: 5375411209848097<br>PayPal: a.vodianko@gmail.com<br><br><strong>Розіграш Світлодіодний LED ліхтар Unibrother LY01 | З роботою до 60 годин | 15600 mAh | 278 LED | 80W за донат з кодом! Кожні 10грн донату - 1 квиток</strong></p>\n<p><strong>Приз виграла <a title="neuroprosthetist" href="../../u/neuroprosthetist">neuroprosthetist</a></strong></p>	18E4505ukqmyVpXDV1_ANqqt67iGsR6Ob9ibdPT-xOEw	/images/banners/52_fpv_01.png	f	27	\N	2023-11-26 17:08:46	2023-12-03 17:56:02
12	zbir_dlya_mizhnarodnogo_legionu_gur_mo	Збір для міжнародного легіону ГУР МО	https://send.monobank.ua/jar/XH3MNagiN	https://www.instagram.com/p/C0UAwoTNKrE/	<p>Командний збір для міжнародного легіону ГУР МО, для підсилення основного збору на: переобладнання та ремонт транспорту, спецприлади (теплаки, нічники, приціли),&nbsp;генератори, старлінки,&nbsp;базові речі для облаштування ПУ, ретранслятори.</p>\n<p>А також розіграш!</p>\n<p><strong>Той, хто задонатить від 200 грн. &mdash; має можливість виграти принт будь-якої моєї фотографії на вибір (<a href="https://instagram.com/planespotter325" target="_blank" rel="noopener">planespotter325</a>), у рамці! Кожні 200 грн. це один квиток</strong></p>\n<p><strong>Той, хто задонатить від 50 грн. &mdash; має можливість виграти книгу "Кримський інжир. Куреш"! Кожні 50 грн. це один квиток</strong></p>\n<p><strong>Той, хто задонатить будь-яку суму &mdash; має можливість виграти одну з десяти авіаційних листівок з моїми фото з різних міст України! Переможців буде обрано випадковим чином, загалом буде розіграно 10 листівок</strong></p>\n<p>🎯 Ціль банки: 25,000 грн.<br>💸 Загальна ціль: 500,000 грн.</p>\n<p>Звіт буде після завершення збору, всім дякую!</p>\n<p>Книга достається <a title="@dubysko" href="../../u/dubysko">@dubysko</a></p>	1qKTLP1LR4sXe00W7YrtrstX4HYYaNaGZdfMIZF2qgI4	https://donater.com.ua/images/banners/planespotter325/Збір-1.png	f	45	\N	2023-12-08 14:41:40	2023-12-09 17:43:35
15	mavik_3t_dlya_128_brigadi	МАВІК 3Т для 128 бригади	https://send.monobank.ua/jar/7zNep36paz?fbclid=IwAR1hewm5iH-g7SZQcyJxD-EnIieQwQBT4WggHtEP2tDwypuYnz9bM5fdMPE	https://www.facebook.com/kolya.klymentovych	<div dir="auto">Унікальний лот - репродукція картини від талановитого іконописця Сергій Колодка на тему <strong>"Запорожці пишуть листа турецькому султанові".</strong></div>\n<div dir="auto">Збір на єдиний дрон з тепловізором для 128 бригади, вони дуже просять. Мінімальна ставка 500 грн.</div>\n<div dir="auto">Велике прохання вказувати свої контакти у разі виграшу для відправки картини.</div>\n<div dir="auto">🎯 Ціль банки: 230 000 грн.<br>💸 Зібрано: 44 000,000 грн.</div>\n<div dir="auto">&nbsp;</div>\n<div dir="auto">&nbsp;</div>\n<div dir="auto">СЛАВА ЗСУ!</div>	1-7UQWTU2RxRtXP2d5Z6nBc2pUlqMTk7rt695n5JnTBs	https://donater.com.ua/images/banners/GrayAccepted/Картина_2.jpg	t	72	2023-12-28 13:30:27	2023-12-27 19:15:57	2023-12-28 13:30:27
14	10_nichnikh_fpv_dlya_52-go_osb	10 НІЧНИХ FPV ДЛЯ 52-ГО ОСБ	https://send.monobank.ua/jar/5qL3tGuQ5t	https://x.com/a_vodianko/status/1733447385865388304?s=20	<p dir="ltr">52-му Окремому Стрілецькому Батальйону потрібні нічні дрони FPV, щоб робити смерть ворогам на Авдіївському напрямку в день та в ночі! Збираємо на 10 дронів, ціль 180.000 грн</p>\n<p dir="ltr">Номер карти моно: 5375411209848097<br>PayPal: a.vodianko@gmail.com</p>\n<p dir="ltr">&nbsp;</p>\n<p dir="ltr">Розіграш призів:</p>\n<ul>\n<li>Powerbank Baseus PPAD000001 Adaman, 10000mAh, 22.5 W</li>\n<li>Книга Позивний для Йова. Хроніки вторгнення - Олександр Михед</li>\n<li>&nbsp;</li>\n</ul>\n<p dir="ltr"><strong>1 квиток - 50 грн з кодом, </strong></p>\n<p dir="ltr"><strong>2 квитки - 100 грн з кодом, і тд</strong></p>\n<p>&nbsp;</p>	16_HiEMFQY1l0O22swu-wkX01RWeVivM8LNnQ_69yeAw	https://donater.com.ua/images/banners/GoldSquealing/ok LOTERIA №2 POST 1x1 UA vol.2 180k.png	t	27	\N	2023-12-15 03:05:13	2023-12-15 17:19:22
10	korch_dlya_1_obrspn	Корч для 1 ОБрСпН	https://send.monobank.ua/jar/4b3GVZzmcM	https://x.com/shidne_bydlo/status/1726526572973498634?s=20	<p>Корчі не вічні, особливо коли підори їбашать їх з ПТУРа. До нас звернувся <a href="https://x.com/SillyNami" target="_blank" rel="noopener">@SillyNami</a> який воює за наші сраки у 1й ОБрСпП ім Івана Богуна. Корчу хана і треба інший. <a href="https://x.com/evhenkyzmenko" target="_blank" rel="noopener">@evhenkyzmenko</a> знайде тачку і пригонить її у Франик.</p>\n<p><strong>Розіграш від <a href="../u/admin" target="_blank" rel="noopener">Серійного Донатера</a>:</strong></p>\n<p><strong>1 квиток - 50 грн з кодом. 100грн донат - 2 квитка. 75грн + 25 грн = 2 квитка. І т.д.</strong></p>\n<p><strong>Призи: 3 (три) переможця буде обрано рандомно</strong></p>\n<hr>\n<ol>\n<li><strong>Baseus 30w charger + Type-C/Type-C 240w 3m + Type-C/Type-C 240w 1m + WITRN Type-C/DC 12v 5A 100w 2m + USB3.1/Type-C перехідник. Такий собі експрес набір для виживання у випадку відключень світла - потужна зарядка швиденько заряти павербанк(30000mAh за 4,5 години), два шнура під зарядку (один три метра, другий метр) шнур під роутер який точно потягне (працює від роз'єму 12v), та перехідник з юсб на тайп сі щоб той шнур включати не тільки в тайп сі</strong></li>\n<li><strong>WITRN Type-C/DC 12v 5A 100w 2m + USB3.1/Type-C перехідник - заживити роутер від павербанку, шнур 2м</strong></li>\n<li><strong>WITRN Type-C/DC 12v 5A 100w 1m + USB3.1/Type-C перехідник - заживити роутер від павербанку, шнур 1м</strong></li>\n</ol>	12u_ISe1HM1xOzniuhSYYwS45kYmrbxjP3CJX91pvano	https://donater.com.ua/images/banners/admin/l200.png	f	36	\N	2023-11-28 02:42:10	2023-12-30 22:02:31
13	taras	taras	https://send.monobank.ua/jar/3jDJbi56Rg	https://www.facebook.com/taras.yarosh.5/posts/pfbid02RrrR6sK8NdAttUJCyMNeQfXPH8YwkPFMuhcidpCsDvZXziuCpjRok2NmB5hV27L6l	<div dir="auto">Наступний лот - тубус "<strong>Багряний</strong>". Збір на дрони для 15 бригади НГУ "Кара-Даг". Наша допомога для захисників триває, будь ласка долучайтесь</div>\n<div dir="auto">Ціль у нас одна Перемога України</div>\n<div dir="auto">Зусилля усі для допомоги захисникам!</div>\n<div dir="auto">PayPal</div>\n<div dir="auto">t.yarosch@gmail.com</div>\n<div dir="auto">Приватбанк:</div>\n<div dir="auto">5168745111258668</div>\n<div dir="auto"><a class="x1i10hfl xjbqb8w x6umtig x1b1mbwd xaqea5y xav7gou x9f619 x1ypdohk xt0psk2 xe8uvvx xdj266r x11i5rnm xat24cr x1mh8g0r xexx8yu x4uap5 x18d9i69 xkhd6sd x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xt0b8zv x1fey0fg" tabindex="0" role="link" href="https://goo-gl.me/ZcaYN?fbclid=IwAR0NBzH52LlcieVr4g3LwSZ6b017yWYc6a39zBUkO_s7E9SCyzuwb8tUmZk" target="_blank" rel="nofollow noopener noreferrer">https://goo-gl.me/ZcaYN</a></div>\n<div dir="auto">monobank:</div>\n<div dir="auto">5375411205154011</div>\n<div dir="auto"><a class="x1i10hfl xjbqb8w x6umtig x1b1mbwd xaqea5y xav7gou x9f619 x1ypdohk xt0psk2 xe8uvvx xdj266r x11i5rnm xat24cr x1mh8g0r xexx8yu x4uap5 x18d9i69 xkhd6sd x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xt0b8zv x1fey0fg" tabindex="0" role="link" href="https://send.monobank.ua/jar/3jDJbi56Rg?fbclid=IwAR0zAHqVg2Ji6XSFjcz36XOBD4F8F-5Qp2sA2W4DtsF-H7YSaQolg5i3tzA" target="_blank" rel="nofollow noopener noreferrer">https://send.monobank.ua/jar/3jDJbi56Rg</a></div>\n<div dir="auto">Криптовалюта</div>\n<div dir="auto"><a class="x1i10hfl xjbqb8w x6umtig x1b1mbwd xaqea5y xav7gou x9f619 x1ypdohk xt0psk2 xe8uvvx xdj266r x11i5rnm xat24cr x1mh8g0r xexx8yu x4uap5 x18d9i69 xkhd6sd x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xt0b8zv x1fey0fg" tabindex="0" role="link" href="https://l.facebook.com/l.php?u=https%3A%2F%2Ftaras-yarosh.pay.whitepay.com%2F%3Ffbclid%3DIwAR1Z75bazWcglOXUyBVescZ26Y4OZH-3UuofbEN1Nv7fqB5wcnMKlTM2mXU&amp;h=AT0jIvnPaELixhDRMEdhs7RTrdF8SQdNAJkWUy50MKi7RIxaebWjWsbvoIkD98NI-JoJavHNh9XQSQPu0N7vQ6M2E9HdtxCIwss1MZi3UZ-WLC10VWcA6B-Wbz5N-Av38UWZ&amp;__tn__=-UK-R&amp;c[0]=AT3GGc6rOQCpXLgcSIXxii1_LRGPzha5o97Hm0QtBKxiO15LZEItG6bvhldbNIILGAvF7kKKjAsLbibbizf7TJincBTIOaxEL_AYMGyorz6XFM5_wDQ-KaLWz9xwY65yF3CSWUloJRzWogI2pvXME-YE1w" target="_blank" rel="nofollow noopener noreferrer">https://taras-yarosh.pay.whitepay.com</a></div>\n<div dir="auto">&nbsp;</div>\n<div dir="auto"><strong>За кожні 50 грн донату з кодом з сайту шанс отримати книжку Анна Гін - Як ти там?</strong></div>	1EMzvmIksz1K0EXo0e1H38lrsstHs8DnCw-IDhAMfu-M	https://donater.com.ua/images/banners/taras_yarosh/photo_2023-12-30_17-29-35.jpg	f	54	\N	2023-12-09 21:10:27	2024-01-01 19:43:30
17	drone_five	drone_five	https://send.monobank.ua/jar/3jDJbi56Rg	https://www.facebook.com/taras.volonter	<p>Відкрито новий збір на 5 дрон для 15 бригади НГУ "Кара-Даг", 1БОП</p>\n<p>Сьогодні кожний з нас воїн,<br>Сьогодні кожний з нас волонтер,<br>Ціль у нас одна Перемога України<br>Зусилля усі &nbsp;для допомоги захисникам!</p>\n<p>PayPal<br>t.yarosch@gmail.com<br>Приватбанк:<br>5168745111258668<br>https://goo-gl.me/ZcaYN<br>monobank:<br>5375411205154011<br>https://send.monobank.ua/jar/3jDJbi56Rg<br>Криптовалюта<br>https://taras-yarosh.pay.whitepay.com<br>донат&nbsp;<br>https://www.buymeacoffee.com/taras_yarosh</p>	1fEt_DmMUAZD4FiJflHvdWhiBgWQxsShW0Xv2nRKBSFM	https://donater.com.ua/images/banners/taras_yarosh/photo_2024-01-01_19-58-01.jpg	t	54	\N	2024-01-01 19:55:00	2024-01-01 19:58:20
16	test	test	https://send.monobank.ua/jar/3irfquacv1	https://send.monobank.ua/jar/3irfquacv1	<p></p>	18E4505ukqmyVpXDV1_ANqqt67iGsR6Ob9ibdPT-xOEw	/images/avatars/avatar.jpeg	f	1	2024-01-01 19:51:01	2024-01-01 19:47:58	2024-01-01 19:51:01
18	10_ultra-long_range_fpv_droniv	10 ultra-long range fpv дронів	https://send.monobank.ua/jar/2ExrHAP4JV	https://t.me/omhareom/13052	<p>НОВИЙ ЗБІР НА СМЕРТЬ ВОРОГАМ</p>\n<p>Нам потрібно закупити для 123 бригади ТрО 10 ultra-long range fpv дронів якомога швидше<br>Зробимо кацапам джингл белз разом😌</p>\n<p>Ціна питання: 270000&nbsp;</p>\n<p><strong>Донат з кодом з сайту - розіграш UGEARS - Привид Києва, деревʼяний 3D конструктор. Один квиток 50грн.&nbsp;</strong></p>	1FojQR4GpBPhfXfTo821TAcQ0s8TgZJAoufmV2fp5N_w	https://donater.com.ua/images/banners/admin/GDGW8ujWMAAi3_d.jpeg	t	82	\N	2024-01-05 18:49:21	2024-01-05 23:17:33
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2016_06_01_000001_create_oauth_auth_codes_table	1
3	2016_06_01_000002_create_oauth_access_tokens_table	1
4	2016_06_01_000003_create_oauth_refresh_tokens_table	1
5	2016_06_01_000004_create_oauth_clients_table	1
6	2016_06_01_000005_create_oauth_personal_access_clients_table	1
7	2019_12_14_000001_create_personal_access_tokens_table	1
8	2023_11_17_163052_create_donates_table	1
9	2023_11_18_142947_create_volunteers_table	1
10	2023_11_19_170144_create_user_links_table	1
11	2023_11_19_210437_add_glits04_1	1
12	2023_11_19_222657_user	1
13	2023_11_21_135848_	1
14	2023_11_22_175428_	1
15	2023_11_22_203356_	1
19	2014_10_12_100000_create_password_resets_table	1
20	2019_08_19_000000_create_failed_jobs_table	1
22	2023_11_17_013614_create_jobs_table	1
33	2014_10_12_000000_create_users_table	2
34	2019_12_14_000001_create_personal_access_tokens_table	2
40	2023_11_17_163052_create_donates_table	3
41	2023_11_18_142947_create_volunteers_table	3
42	2023_11_19_170144_create_user_links_table	4
43	2023_11_19_210437_add_glits04_1	5
44	2023_11_19_222657_user	6
45	2023_11_20_162856_create_cache_table	7
46	2023_11_21_135848_	8
47	2023_11_22_175428_	9
48	2023_11_22_203356_	10
49	2016_06_01_000001_create_oauth_auth_codes_table	11
50	2016_06_01_000002_create_oauth_access_tokens_table	11
51	2016_06_01_000003_create_oauth_refresh_tokens_table	11
52	2016_06_01_000004_create_oauth_clients_table	11
53	2016_06_01_000005_create_oauth_personal_access_clients_table	11
54	2024_01_01_162958_rename_volunteers_table	12
55	2024_01_01_163441_donate_table_rename_volunteer_id	12
56	2024_01_04_121128_create_user_codes_table	12
57	2024_01_04_230731_donate	12
\.


--
-- Data for Name: oauth_access_tokens; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.oauth_access_tokens (id, user_id, client_id, name, scopes, revoked, created_at, updated_at, expires_at) FROM stdin;
\.


--
-- Data for Name: oauth_auth_codes; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.oauth_auth_codes (id, user_id, client_id, scopes, revoked, expires_at) FROM stdin;
\.


--
-- Data for Name: oauth_clients; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.oauth_clients (id, user_id, name, secret, provider, redirect, personal_access_client, password_client, revoked, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: oauth_personal_access_clients; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.oauth_personal_access_clients (id, client_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: oauth_refresh_tokens; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.oauth_refresh_tokens (id, access_token_id, revoked, expires_at) FROM stdin;
\.


--
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: user_codes; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.user_codes (id, hash, user_id, created_at, updated_at) FROM stdin;
1	Vzs8E	81	2024-01-05 00:52:16	2024-01-05 00:52:16
2	65972e790fd713.86611998	81	2024-01-05 00:54:37	2024-01-05 00:54:37
3	65958b9711feb8.52291853	42	2024-01-05 00:54:37	2024-01-05 00:54:37
4	659587575d6d71.68532446	41	2024-01-05 00:54:37	2024-01-05 00:54:37
5	6595877ff23a28.49215978	13	2024-01-05 00:54:37	2024-01-05 00:54:37
6	6581e75d8a0e18.82527552	67	2024-01-05 00:54:37	2024-01-05 00:54:37
7	6578c2ac0a5258.93915692	58	2024-01-05 00:54:37	2024-01-05 00:54:37
8	6581b1ed41a298.97032823	27	2024-01-05 00:54:37	2024-01-05 00:54:37
9	6581e18f415e48.48580820	7	2024-01-05 00:54:37	2024-01-05 00:54:37
10	65915722662ed0.05583396	27	2024-01-05 00:54:37	2024-01-05 00:54:37
11	6591981abe1730.65423857	54	2024-01-05 00:54:37	2024-01-05 00:54:37
12	659197ef0491a7.14413338	54	2024-01-05 00:54:37	2024-01-05 00:54:37
13	659197c1c0c2a7.43427672	54	2024-01-05 00:54:37	2024-01-05 00:54:37
14	65915b44064fd2.75057091	27	2024-01-05 00:54:37	2024-01-05 00:54:37
15	65919790c820c5.07007887	54	2024-01-05 00:54:37	2024-01-05 00:54:37
16	65928372c4d294.33312715	78	2024-01-05 00:54:37	2024-01-05 00:54:37
17	659282a7dc2758.82742739	78	2024-01-05 00:54:37	2024-01-05 00:54:37
18	6591971b879f66.33073603	54	2024-01-05 00:54:37	2024-01-05 00:54:37
19	659078ff750454.42788242	73	2024-01-05 00:54:37	2024-01-05 00:54:37
20	65915d4245c454.18157587	27	2024-01-05 00:54:37	2024-01-05 00:54:37
21	659157edc7d808.18366889	77	2024-01-05 00:54:37	2024-01-05 00:54:37
22	65915753a845b4.69037860	69	2024-01-05 00:54:37	2024-01-05 00:54:37
23	65913cd4687682.19939953	9	2024-01-05 00:54:37	2024-01-05 00:54:37
24	65913cbb15e617.89425255	9	2024-01-05 00:54:37	2024-01-05 00:54:37
25	65913ca5786068.13348904	9	2024-01-05 00:54:37	2024-01-05 00:54:37
26	65913c8be31223.12095400	9	2024-01-05 00:54:37	2024-01-05 00:54:37
27	65913c736b9117.69245343	9	2024-01-05 00:54:37	2024-01-05 00:54:37
28	65913c55583702.62335276	9	2024-01-05 00:54:37	2024-01-05 00:54:37
29	65912f4bca5657.20974991	76	2024-01-05 00:54:37	2024-01-05 00:54:37
30	65909c9b3d8349.82248043	74	2024-01-05 00:54:37	2024-01-05 00:54:37
31	65909bcf899322.68607841	74	2024-01-05 00:54:37	2024-01-05 00:54:37
32	65909b4e49d776.26649494	74	2024-01-05 00:54:37	2024-01-05 00:54:37
33	65907afe7ee304.97018247	73	2024-01-05 00:54:37	2024-01-05 00:54:37
34	6590796e5dfee8.90148392	41	2024-01-05 00:54:37	2024-01-05 00:54:37
35	658a0e43643388.45893189	1	2024-01-05 00:54:37	2024-01-05 00:54:37
36	658a0e18054b97.83134108	1	2024-01-05 00:54:37	2024-01-05 00:54:37
37	658a0d1417afb6.81985908	1	2024-01-05 00:54:37	2024-01-05 00:54:37
38	65872c60eaf9a5.03174244	41	2024-01-05 00:54:37	2024-01-05 00:54:37
39	6582269b297437.65426428	42	2024-01-05 00:54:37	2024-01-05 00:54:37
40	658225488d8d26.58805594	42	2024-01-05 00:54:37	2024-01-05 00:54:37
41	658224e3685309.98071422	42	2024-01-05 00:54:37	2024-01-05 00:54:37
42	6581ab7ee6e271.31328376	1	2024-01-05 00:54:37	2024-01-05 00:54:37
43	6581ab5e6e64f1.51649806	1	2024-01-05 00:54:37	2024-01-05 00:54:37
44	6581aaf774d1f9.93065386	1	2024-01-05 00:54:37	2024-01-05 00:54:37
45	65818cd80cca17.54013753	13	2024-01-05 00:54:37	2024-01-05 00:54:37
46	6580d1fdc95e93.77061186	65	2024-01-05 00:54:37	2024-01-05 00:54:37
47	6580d16722ffd8.18994109	65	2024-01-05 00:54:37	2024-01-05 00:54:37
48	65805161904a17.01225995	9	2024-01-05 00:54:37	2024-01-05 00:54:37
49	6580514c88e0f6.12269704	9	2024-01-05 00:54:37	2024-01-05 00:54:37
50	6580513800c417.29106445	9	2024-01-05 00:54:37	2024-01-05 00:54:37
51	65805120bcbac6.99979616	9	2024-01-05 00:54:37	2024-01-05 00:54:37
52	657e259052e428.75093549	1	2024-01-05 00:54:37	2024-01-05 00:54:37
53	657e255fe169b9.31429984	1	2024-01-05 00:54:37	2024-01-05 00:54:37
54	657e252e429c60.78022598	1	2024-01-05 00:54:37	2024-01-05 00:54:37
55	657dc35d99ab25.81605781	63	2024-01-05 00:54:37	2024-01-05 00:54:37
56	657390cae1cb88.57861440	47	2024-01-05 00:54:37	2024-01-05 00:54:37
57	6579b66e88d1e2.55546917	59	2024-01-05 00:54:37	2024-01-05 00:54:37
58	657adb85979ea3.93195123	60	2024-01-05 00:54:37	2024-01-05 00:54:37
59	657af1e861d3c1.84149064	1	2024-01-05 00:54:37	2024-01-05 00:54:37
60	657adde7cde6e7.76336773	60	2024-01-05 00:54:37	2024-01-05 00:54:37
61	657adc870b2f75.10531153	60	2024-01-05 00:54:37	2024-01-05 00:54:37
62	657a44ac2652e7.41623747	27	2024-01-05 00:54:37	2024-01-05 00:54:37
63	6579b67ec1d602.67398614	59	2024-01-05 00:54:37	2024-01-05 00:54:37
64	6579a326b0bcf6.75724919	9	2024-01-05 00:54:37	2024-01-05 00:54:37
65	6579a31270df37.82181918	9	2024-01-05 00:54:37	2024-01-05 00:54:37
66	6579a2f6144f18.36029873	9	2024-01-05 00:54:37	2024-01-05 00:54:37
67	6578c2bfe129a9.30039376	58	2024-01-05 00:54:37	2024-01-05 00:54:37
68	6578a292a2eca1.38660369	9	2024-01-05 00:54:37	2024-01-05 00:54:37
69	6578a26b397ab9.32679530	9	2024-01-05 00:54:37	2024-01-05 00:54:37
70	6578a2521690d2.76336137	9	2024-01-05 00:54:37	2024-01-05 00:54:37
71	6578a23aa49de0.58934826	9	2024-01-05 00:54:37	2024-01-05 00:54:37
72	6578a216d52649.90831998	9	2024-01-05 00:54:37	2024-01-05 00:54:37
73	65785ab67ec5b4.85252037	1	2024-01-05 00:54:37	2024-01-05 00:54:37
74	65785a801fdbc6.25824687	1	2024-01-05 00:54:37	2024-01-05 00:54:37
75	65785a4886c144.26256277	1	2024-01-05 00:54:37	2024-01-05 00:54:37
76	657707ec461f20.84925783	45	2024-01-05 00:54:37	2024-01-05 00:54:37
77	6575c728359837.09702775	52	2024-01-05 00:54:37	2024-01-05 00:54:37
78	6575c695c54117.42920680	53	2024-01-05 00:54:37	2024-01-05 00:54:37
79	6575c2fdb22ba7.03906210	54	2024-01-05 00:54:37	2024-01-05 00:54:37
80	6575c31d2e8852.97781365	54	2024-01-05 00:54:37	2024-01-05 00:54:37
81	6574e52b95ad22.52885389	56	2024-01-05 00:54:37	2024-01-05 00:54:37
82	6574c508a91673.36906471	55	2024-01-05 00:54:37	2024-01-05 00:54:37
83	6574a51a7ccd79.24636786	13	2024-01-05 00:54:37	2024-01-05 00:54:37
84	6574a47ad90a58.00155199	9	2024-01-05 00:54:37	2024-01-05 00:54:37
85	6574a44f601fc7.08264179	9	2024-01-05 00:54:37	2024-01-05 00:54:37
86	6574a43260e664.37407912	9	2024-01-05 00:54:37	2024-01-05 00:54:37
87	6574a417e62873.30042658	9	2024-01-05 00:54:37	2024-01-05 00:54:37
88	6574a402199790.04926091	9	2024-01-05 00:54:37	2024-01-05 00:54:37
89	6574a3eb24fa43.95504034	9	2024-01-05 00:54:37	2024-01-05 00:54:37
90	6574a3d3b8fc50.87153928	9	2024-01-05 00:54:37	2024-01-05 00:54:37
91	6574a3b76e76b1.48927411	9	2024-01-05 00:54:37	2024-01-05 00:54:37
92	6574516ec8f4e1.00105415	50	2024-01-05 00:54:37	2024-01-05 00:54:37
93	657382286c17f7.49557034	1	2024-01-05 00:54:37	2024-01-05 00:54:37
94	65731cd5c10e68.28834401	9	2024-01-05 00:54:37	2024-01-05 00:54:37
95	65731cb084f0a2.51919913	9	2024-01-05 00:54:37	2024-01-05 00:54:37
96	65731c97245c38.09051992	9	2024-01-05 00:54:37	2024-01-05 00:54:37
97	65731c7d7ad2b7.78935135	9	2024-01-05 00:54:37	2024-01-05 00:54:37
98	65731c55974133.65680757	9	2024-01-05 00:54:37	2024-01-05 00:54:37
99	6572aea0df45d5.37970667	46	2024-01-05 00:54:37	2024-01-05 00:54:37
100	657202e1b77437.83149167	13	2024-01-05 00:54:37	2024-01-05 00:54:37
101	65710167d21897.37267599	42	2024-01-05 00:54:37	2024-01-05 00:54:37
102	6570aef62f5149.05967534	18	2024-01-05 00:54:37	2024-01-05 00:54:37
103	6570a6fd7a9fc9.49682303	13	2024-01-05 00:54:37	2024-01-05 00:54:37
104	6570a6da5facf7.28542453	13	2024-01-05 00:54:37	2024-01-05 00:54:37
105	6570a3fed38900.26274525	41	2024-01-05 00:54:37	2024-01-05 00:54:37
106	6570a388cacc05.68727855	41	2024-01-05 00:54:37	2024-01-05 00:54:37
107	6570a2884ec7d2.99202585	1	2024-01-05 00:54:37	2024-01-05 00:54:37
108	65702b4edf73b8.03043050	31	2024-01-05 00:54:37	2024-01-05 00:54:37
109	656eeecbdf52c8.71578152	9	2024-01-05 00:54:37	2024-01-05 00:54:37
110	656eeeaba62861.93497869	9	2024-01-05 00:54:37	2024-01-05 00:54:37
111	656eee75b0bf59.41105200	9	2024-01-05 00:54:37	2024-01-05 00:54:37
112	656e43ff3bf6b6.52495152	7	2024-01-05 00:54:37	2024-01-05 00:54:37
113	656b0e8a9d67d0.89225533	1	2024-01-05 00:54:37	2024-01-05 00:54:37
114	6569a182b18c63.34813729	1	2024-01-05 00:54:37	2024-01-05 00:54:37
115	6569005daa0948.11000368	41	2024-01-05 00:54:37	2024-01-05 00:54:37
116	6568ffaea1ee83.21826949	41	2024-01-05 00:54:37	2024-01-05 00:54:37
117	6568ddf2ed10e4.83189405	1	2024-01-05 00:54:37	2024-01-05 00:54:37
118	65679e2de090c7.67654695	24	2024-01-05 00:54:37	2024-01-05 00:54:37
119	656741b3b7f408.16538817	18	2024-01-05 00:54:37	2024-01-05 00:54:37
120	6567a04a40c760.76121848	24	2024-01-05 00:54:37	2024-01-05 00:54:37
121	65679eaea90320.57247586	1	2024-01-05 00:54:37	2024-01-05 00:54:37
122	65679e95b66cb7.26371958	7	2024-01-05 00:54:37	2024-01-05 00:54:37
123	65679c0c38ef77.33782785	40	2024-01-05 00:54:37	2024-01-05 00:54:37
124	65679b84c1b052.19293789	19	2024-01-05 00:54:37	2024-01-05 00:54:37
125	656790fa787869.33743920	7	2024-01-05 00:54:37	2024-01-05 00:54:37
126	65679117df73f5.04285875	13	2024-01-05 00:54:37	2024-01-05 00:54:37
127	65676e6e8fb8d1.64650806	39	2024-01-05 00:54:37	2024-01-05 00:54:37
128	65675bc95af215.40447781	1	2024-01-05 00:54:37	2024-01-05 00:54:37
129	65675ba2628981.23519114	1	2024-01-05 00:54:37	2024-01-05 00:54:37
130	65675b6ab59a94.95233172	1	2024-01-05 00:54:37	2024-01-05 00:54:37
131	6566bcc5cb1a40.83789456	7	2024-01-05 00:54:37	2024-01-05 00:54:37
132	6566562de5fc83.12843621	37	2024-01-05 00:54:37	2024-01-05 00:54:37
133	65665588f1eff4.00130112	13	2024-01-05 00:54:37	2024-01-05 00:54:37
134	6566546b38f513.55055746	1	2024-01-05 00:54:37	2024-01-05 00:54:37
135	6566265f22fb26.50412947	36	2024-01-05 00:54:37	2024-01-05 00:54:37
136	65661aea9fb245.89740899	16	2024-01-05 00:54:37	2024-01-05 00:54:37
137	656607a0427e87.53837856	1	2024-01-05 00:54:37	2024-01-05 00:54:37
138	6566075ed21253.18109544	1	2024-01-05 00:54:37	2024-01-05 00:54:37
139	6565c68c2ae7f3.66962952	1	2024-01-05 00:54:37	2024-01-05 00:54:37
140	6565c1acea0362.22725500	33	2024-01-05 00:54:37	2024-01-05 00:54:37
141	6565be9137d015.23642914	32	2024-01-05 00:54:37	2024-01-05 00:54:37
142	6565bb26a57994.14553137	31	2024-01-05 00:54:37	2024-01-05 00:54:37
143	6565b86553d382.45368985	31	2024-01-05 00:54:37	2024-01-05 00:54:37
144	65645c113630f7.52679678	30	2024-01-05 00:54:37	2024-01-05 00:54:37
145	656475241ed540.77174408	1	2024-01-05 00:54:37	2024-01-05 00:54:37
146	656474e56923a6.39450835	1	2024-01-05 00:54:37	2024-01-05 00:54:37
147	65646a27898262.98398604	9	2024-01-05 00:54:37	2024-01-05 00:54:37
148	656469e28a0296.05032767	9	2024-01-05 00:54:37	2024-01-05 00:54:37
149	656469a9d58b29.48003005	9	2024-01-05 00:54:37	2024-01-05 00:54:37
150	6564698ba739b8.91237837	9	2024-01-05 00:54:37	2024-01-05 00:54:37
151	6564509e9c34c	29	2024-01-05 00:54:37	2024-01-05 00:54:37
152	6563f602baa48	3	2024-01-05 00:54:38	2024-01-05 00:54:38
153	6563f5b5e4293	3	2024-01-05 00:54:38	2024-01-05 00:54:38
154	6563b3f889000	28	2024-01-05 00:54:38	2024-01-05 00:54:38
155	6563a2813f771	1	2024-01-05 00:54:38	2024-01-05 00:54:38
156	6563a238e3637	1	2024-01-05 00:54:38	2024-01-05 00:54:38
157	6562ec0416602	11	2024-01-05 00:54:38	2024-01-05 00:54:38
158	656298ad8ce26	1	2024-01-05 00:54:38	2024-01-05 00:54:38
159	6562985b978d5	1	2024-01-05 00:54:38	2024-01-05 00:54:38
160	65625b4f7d0f1	7	2024-01-05 00:54:38	2024-01-05 00:54:38
161	65621462b74a8	9	2024-01-05 00:54:38	2024-01-05 00:54:38
162	65621435bf114	9	2024-01-05 00:54:38	2024-01-05 00:54:38
163	6561e693bd8d9	13	2024-01-05 00:54:38	2024-01-05 00:54:38
164	6561e3426e9fb	13	2024-01-05 00:54:38	2024-01-05 00:54:38
165	6561e648f1fb6	13	2024-01-05 00:54:38	2024-01-05 00:54:38
166	6560f85fb31f9	7	2024-01-05 00:54:38	2024-01-05 00:54:38
167	65609f222c5f9	9	2024-01-05 00:54:38	2024-01-05 00:54:38
168	65609ef76f69a	9	2024-01-05 00:54:38	2024-01-05 00:54:38
169	65609ed62f10c	9	2024-01-05 00:54:38	2024-01-05 00:54:38
170	655fb41e1be0e	25	2024-01-05 00:54:38	2024-01-05 00:54:38
171	655fb415c9eeb	26	2024-01-05 00:54:38	2024-01-05 00:54:38
172	655fa21055daf	7	2024-01-05 00:54:38	2024-01-05 00:54:38
173	655f88f8e83ae	1	2024-01-05 00:54:38	2024-01-05 00:54:38
174	655f3f821f334	9	2024-01-05 00:54:38	2024-01-05 00:54:38
175	655e63a398b99	21	2024-01-05 00:54:38	2024-01-05 00:54:38
176	655e6dcf09a41	1	2024-01-05 00:54:38	2024-01-05 00:54:38
177	655e6b2376038	22	2024-01-05 00:54:38	2024-01-05 00:54:38
178	655e63ff9a190	1	2024-01-05 00:54:38	2024-01-05 00:54:38
179	655e63bf7149f	1	2024-01-05 00:54:38	2024-01-05 00:54:38
180	655e18bb0bdf0	1	2024-01-05 00:54:38	2024-01-05 00:54:38
181	655d9c1bb94b9	9	2024-01-05 00:54:38	2024-01-05 00:54:38
182	655d49cc1ad16	1	2024-01-05 00:54:38	2024-01-05 00:54:38
183	655d4984de01f	1	2024-01-05 00:54:38	2024-01-05 00:54:38
184	655d49124f7c5	1	2024-01-05 00:54:38	2024-01-05 00:54:38
185	655d129ad5a03	13	2024-01-05 00:54:38	2024-01-05 00:54:38
186	655d0d9f03343	19	2024-01-05 00:54:38	2024-01-05 00:54:38
187	655d0d532599e	7	2024-01-05 00:54:38	2024-01-05 00:54:38
188	655d0d6498b54	20	2024-01-05 00:54:38	2024-01-05 00:54:38
189	655cf6db8c2c3	1	2024-01-05 00:54:38	2024-01-05 00:54:38
190	655cc9bebccbe	18	2024-01-05 00:54:38	2024-01-05 00:54:38
191	655cb65c0e591	1	2024-01-05 00:54:38	2024-01-05 00:54:38
192	655cb5601b554	1	2024-01-05 00:54:38	2024-01-05 00:54:38
193	655c91ebeb1bb	3	2024-01-05 00:54:38	2024-01-05 00:54:38
194	655c8d722efc0	16	2024-01-05 00:54:38	2024-01-05 00:54:38
195	655c890c43751	7	2024-01-05 00:54:38	2024-01-05 00:54:38
196	655c830e11101	9	2024-01-05 00:54:38	2024-01-05 00:54:38
197	655bdecb4e015	1	2024-01-05 00:54:38	2024-01-05 00:54:38
198	655bc6ea46ee2	2	2024-01-05 00:54:38	2024-01-05 00:54:38
199	655bc6b1898e6	2	2024-01-05 00:54:38	2024-01-05 00:54:38
200	655bc5e8be555	1	2024-01-05 00:54:38	2024-01-05 00:54:38
201	655b7e1996556	1	2024-01-05 00:54:38	2024-01-05 00:54:38
202	655b655ec5ef5	14	2024-01-05 00:54:38	2024-01-05 00:54:38
203	655b636a634f4	13	2024-01-05 00:54:38	2024-01-05 00:54:38
204	655b50f6e9186	7	2024-01-05 00:54:38	2024-01-05 00:54:38
205	655b473f08897	12	2024-01-05 00:54:38	2024-01-05 00:54:38
206	655b471893201	12	2024-01-05 00:54:38	2024-01-05 00:54:38
207	655b46ece844d	12	2024-01-05 00:54:38	2024-01-05 00:54:38
208	655b46c0d702f	12	2024-01-05 00:54:38	2024-01-05 00:54:38
209	655b42c9e61e0	12	2024-01-05 00:54:38	2024-01-05 00:54:38
210	655b426686de2	12	2024-01-05 00:54:38	2024-01-05 00:54:38
211	655af5dcb4b28	9	2024-01-05 00:54:38	2024-01-05 00:54:38
212	655a92da1add6	10	2024-01-05 00:54:38	2024-01-05 00:54:38
213	655a92b942dbe	10	2024-01-05 00:54:38	2024-01-05 00:54:38
214	655a925a7e6d9	10	2024-01-05 00:54:38	2024-01-05 00:54:38
215	655a9233b4055	10	2024-01-05 00:54:38	2024-01-05 00:54:38
216	655a91f66ded0	10	2024-01-05 00:54:38	2024-01-05 00:54:38
217	655a8e77b9dfe	1	2024-01-05 00:54:38	2024-01-05 00:54:38
218	655a28b56f3f4	7	2024-01-05 00:54:38	2024-01-05 00:54:38
219	655973f92c934	3	2024-01-05 00:54:38	2024-01-05 00:54:38
220	655973c402ce3	3	2024-01-05 00:54:38	2024-01-05 00:54:38
221	6559734016d1c	3	2024-01-05 00:54:38	2024-01-05 00:54:38
222	655972dce2257	3	2024-01-05 00:54:38	2024-01-05 00:54:38
223	655925323dfad	1	2024-01-05 00:54:38	2024-01-05 00:54:38
224	72WXh	81	2024-01-05 00:54:38	2024-01-05 00:54:38
225	LAf6J	80	2024-01-05 00:54:38	2024-01-05 00:54:38
226	fEsaz	79	2024-01-05 00:54:38	2024-01-05 00:54:38
227	EML32	78	2024-01-05 00:54:38	2024-01-05 00:54:38
228	AtRrU	77	2024-01-05 00:54:38	2024-01-05 00:54:38
229	FpZPH	76	2024-01-05 00:54:38	2024-01-05 00:54:38
230	GPNtz	75	2024-01-05 00:54:38	2024-01-05 00:54:38
231	EQHkS	74	2024-01-05 00:54:38	2024-01-05 00:54:38
232	MWmqa	73	2024-01-05 00:54:38	2024-01-05 00:54:38
233	H09Ye	72	2024-01-05 00:54:38	2024-01-05 00:54:38
234	JBTGq	71	2024-01-05 00:54:38	2024-01-05 00:54:38
235	HNwRZ	70	2024-01-05 00:54:38	2024-01-05 00:54:38
236	3NssC	69	2024-01-05 00:54:38	2024-01-05 00:54:38
237	bQ6kH	68	2024-01-05 00:54:38	2024-01-05 00:54:38
238	RertD	67	2024-01-05 00:54:38	2024-01-05 00:54:38
239	9Vb5c	66	2024-01-05 00:54:38	2024-01-05 00:54:38
240	VlNyt	65	2024-01-05 00:54:38	2024-01-05 00:54:38
241	oTcTq	64	2024-01-05 00:54:38	2024-01-05 00:54:38
242	yigLv	63	2024-01-05 00:54:38	2024-01-05 00:54:38
243	V7K7c	62	2024-01-05 00:54:38	2024-01-05 00:54:38
244	SR9nl	61	2024-01-05 00:54:38	2024-01-05 00:54:38
245	fOCD8	60	2024-01-05 00:54:38	2024-01-05 00:54:38
246	pTLQA	59	2024-01-05 00:54:38	2024-01-05 00:54:38
247	8o1wk	58	2024-01-05 00:54:38	2024-01-05 00:54:38
248	tomVx	57	2024-01-05 00:54:38	2024-01-05 00:54:38
249	gHYlU	56	2024-01-05 00:54:38	2024-01-05 00:54:38
250	gjTGo	55	2024-01-05 00:54:38	2024-01-05 00:54:38
251	rfiHX	54	2024-01-05 00:54:38	2024-01-05 00:54:38
252	1eywQ	53	2024-01-05 00:54:38	2024-01-05 00:54:38
253	7PQNr	52	2024-01-05 00:54:38	2024-01-05 00:54:38
254	PC9ok	51	2024-01-05 00:54:38	2024-01-05 00:54:38
255	eQosJ	50	2024-01-05 00:54:38	2024-01-05 00:54:38
256	Nh7hr	49	2024-01-05 00:54:38	2024-01-05 00:54:38
257	0Trui	48	2024-01-05 00:54:38	2024-01-05 00:54:38
258	DAkWn	47	2024-01-05 00:54:38	2024-01-05 00:54:38
259	5lVzg	46	2024-01-05 00:54:38	2024-01-05 00:54:38
260	cOuBG	45	2024-01-05 00:54:38	2024-01-05 00:54:38
261	GWlAk	44	2024-01-05 00:54:38	2024-01-05 00:54:38
262	JlbXO	43	2024-01-05 00:54:38	2024-01-05 00:54:38
263	nArMI	42	2024-01-05 00:54:38	2024-01-05 00:54:38
264	GqBfA	41	2024-01-05 00:54:38	2024-01-05 00:54:38
265	o6E53	40	2024-01-05 00:54:38	2024-01-05 00:54:38
266	407st	39	2024-01-05 00:54:38	2024-01-05 00:54:38
267	St8Nw	38	2024-01-05 00:54:38	2024-01-05 00:54:38
268	FlNpG	37	2024-01-05 00:54:38	2024-01-05 00:54:38
269	FaJcR	36	2024-01-05 00:54:38	2024-01-05 00:54:38
270	x1242	35	2024-01-05 00:54:38	2024-01-05 00:54:38
271	Khphb	34	2024-01-05 00:54:38	2024-01-05 00:54:38
272	3GC34	33	2024-01-05 00:54:38	2024-01-05 00:54:38
273	6cF6X	32	2024-01-05 00:54:38	2024-01-05 00:54:38
274	RCRuf	31	2024-01-05 00:54:38	2024-01-05 00:54:38
275	NDQ4P	30	2024-01-05 00:54:38	2024-01-05 00:54:38
276	YXW3Q	29	2024-01-05 00:54:38	2024-01-05 00:54:38
277	RNNhE	28	2024-01-05 00:54:38	2024-01-05 00:54:38
278	rdxaa	27	2024-01-05 00:54:38	2024-01-05 00:54:38
279	vlch8	26	2024-01-05 00:54:38	2024-01-05 00:54:38
280	heyT4	25	2024-01-05 00:54:38	2024-01-05 00:54:38
281	5ADtJ	24	2024-01-05 00:54:38	2024-01-05 00:54:38
282	GQhnK	23	2024-01-05 00:54:38	2024-01-05 00:54:38
283	vaibj	22	2024-01-05 00:54:38	2024-01-05 00:54:38
284	4WGkW	21	2024-01-05 00:54:38	2024-01-05 00:54:38
285	MSBI3	20	2024-01-05 00:54:38	2024-01-05 00:54:38
286	Fvt1I	19	2024-01-05 00:54:38	2024-01-05 00:54:38
287	DlKVG	18	2024-01-05 00:54:38	2024-01-05 00:54:38
288	ejIH2	17	2024-01-05 00:54:38	2024-01-05 00:54:38
289	wemXs	16	2024-01-05 00:54:38	2024-01-05 00:54:38
290	e695d	15	2024-01-05 00:54:38	2024-01-05 00:54:38
291	1WyWe	14	2024-01-05 00:54:38	2024-01-05 00:54:38
292	Wgm1V	13	2024-01-05 00:54:38	2024-01-05 00:54:38
293	Pfaka	12	2024-01-05 00:54:38	2024-01-05 00:54:38
294	loqsA	11	2024-01-05 00:54:38	2024-01-05 00:54:38
295	RnodA	10	2024-01-05 00:54:38	2024-01-05 00:54:38
296	8EDoz	9	2024-01-05 00:54:38	2024-01-05 00:54:38
297	pmvtG	8	2024-01-05 00:54:38	2024-01-05 00:54:38
298	vBQ1J	7	2024-01-05 00:54:38	2024-01-05 00:54:38
299	3feBP	6	2024-01-05 00:54:38	2024-01-05 00:54:38
300	V3s33	5	2024-01-05 00:54:38	2024-01-05 00:54:38
301	YZL2g	4	2024-01-05 00:54:38	2024-01-05 00:54:38
302	GNc9i	3	2024-01-05 00:54:38	2024-01-05 00:54:38
303	h6OCC	2	2024-01-05 00:54:38	2024-01-05 00:54:38
304	iF8jy	1	2024-01-05 00:54:38	2024-01-05 00:54:38
305	oMkRA	82	2024-01-05 18:45:49	2024-01-05 18:45:49
\.


--
-- Data for Name: user_links; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.user_links (id, user_id, link, name, icon, created_at, updated_at) FROM stdin;
3	3	https://twitter.com/setnemo	Хуітер	bi bi-twitter-x	2023-11-19 19:47:31	2023-11-19 19:47:31
4	3	https://github.com/setnemo	GitHub	bi bi-github	2023-11-19 19:48:31	2023-11-19 19:48:31
5	3	https://bsky.app/profile/setnemo.online	BlueSky	bi bi-twitter	2023-11-19 19:49:39	2023-11-19 19:49:39
6	3	https://setnemo.online	setnemo.online	bi bi-globe	2023-11-19 19:50:33	2023-11-19 19:50:33
7	10	https://x.com/vvoldi47	Twitter	bi bi-twitter	2023-11-20 00:56:57	2023-11-20 00:56:57
9	2	https://x.com/hannapetrivna?t=exLBtLtXd2PfjRxgOwEYKg&s=09	хуїтер	bi bi-twitter	2023-11-20 02:17:38	2023-11-20 02:17:38
10	13	https://twitter.com/xlr2mp3	Twitter/X	bi bi-twitter-x	2023-11-20 15:47:22	2023-11-20 15:47:22
11	1	https://donater.com.ua/u/setnemo	setnemo	bi bi-globe	2023-11-21 01:03:26	2023-11-21 01:03:26
12	1	https://donater.com.ua/my	Твоя сторінка	bi bi-globe	2023-11-21 01:03:56	2023-11-21 01:03:56
13	13	https://www.youtube.com/channel/UC2plWG-K0OITmwD5ESbO14Q	Youtube	bi bi-youtube	2023-11-21 22:47:22	2023-11-21 22:47:22
14	13	https://open.spotify.com/artist/5hCvBstdBvUzDNpnXyeAag	Spotify	bi bi-spotify	2023-11-21 22:47:45	2023-11-21 22:47:45
15	13	https://bsky.app/profile/vo1dee.bsky.social	Blue Sky	bi bi-behance	2023-11-21 22:53:25	2023-11-21 22:53:25
16	3	https://donater.com.ua/u/admin	donater.com.ua	bi bi-paypal	2023-11-27 11:07:51	2023-11-27 11:07:51
17	27	https://twitter.com/a_vodianko	twitter	bi bi-twitter	2023-11-27 21:01:22	2023-11-27 21:01:22
19	33	https://bit.ly/VictoryDrones	Курс 'Інженер БПЛА'	bi bi-globe	2023-11-28 12:42:05	2023-11-28 12:42:05
20	54	https://www.facebook.com/taras.yarosh.5/posts/pfbid0Ws7YSyRFLf7MkzHfzQGgE1ws7ZiRKg6dB1vW3j6bZqYDLswe1SU72d8VTk8Ehdsl	лотерея	bi bi-facebook	2023-12-09 21:05:51	2023-12-09 21:05:51
21	60	https://github.com/wobondar	GitHub	bi bi-github	2023-12-14 12:42:56	2023-12-14 12:42:56
22	60	https://www.linkedin.com/in/bondarua/	LinkedIn	bi bi-linkedin	2023-12-14 12:46:57	2023-12-14 12:46:57
23	71	https://twitter.com/sera_nikulin	твітер	bi bi-twitter-x	2023-12-26 21:44:31	2023-12-26 21:44:31
24	71	https://www.instagram.com/sera_nikulin/	інстаграм	bi bi-instagram	2023-12-26 21:45:00	2023-12-26 21:45:00
25	72	https://www.facebook.com/kolya.klymentovych	Микола	bi bi-globe	2023-12-27 19:18:15	2023-12-27 19:18:15
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.users (id, username, telegram_id, first_name, last_name, avatar, is_premium, created_at, updated_at) FROM stdin;
16	vharitonsky	366450479	Виталий	Харитонский	/images/avatars/vharitonsky/XlAJOhEEeStTwCvAtbaafO40a0dYcys1x3mVg0VF.jpg	f	2023-11-21 12:58:16	2023-11-21 12:58:16
17	ilototska	471227235	Lototska		/images/avatars/ilototska/dVsumQsGDb8kcX5l9CzSlUvob2XwiRO1bBsoFbN7.jpg	f	2023-11-21 16:00:09	2023-11-21 16:00:09
18	taras_10	186769002	Taras		/images/avatars/avatar.jpeg	f	2023-11-21 17:16:13	2023-11-21 17:16:13
19	kariiina_d	393773886	Karyna		/images/avatars/avatar.jpeg	f	2023-11-21 22:02:13	2023-11-21 22:02:13
20	vladisLova	337400651	Vladyslava	Titarenko	/images/avatars/vladisLova/fi5HwsmeA3G3lfPhhUPrGhdVxYnL7kSeeH0qj88z.jpg	f	2023-11-21 22:04:51	2023-11-21 22:04:51
4	nathan_brunt	393416420	Сергец		/images/avatars/avatar.jpeg	f	2023-11-19 14:43:54	2023-11-19 14:43:54
5	nstwtn	242924463	Anastasia		/images/avatars/nstwtn/Ei5Bj6bwgRLIOcK4Gzdrt104SmvnobZLXm3fmJr7.jpg	f	2023-11-19 15:04:34	2023-11-19 15:04:34
6	DekoSanae	654430707	grateful		/images/avatars/DekoSanae/iXsu8BS7QbXCOnyFPaPqk4p7Zv7NCJ646q1KWwyn.jpg	t	2023-11-19 16:17:20	2023-11-19 16:17:20
7	vossapov	514224860	Valentyn	Ihnatov	/images/avatars/vossapov/ra2B7mDhVbdsZOUXiaGYIkSZ2o7rZIgHUuMjSBJ3.jpg	f	2023-11-19 17:24:27	2023-11-19 17:24:27
8	JuliaKostynska	444959544	Iuliia	Kostynska	/images/avatars/JuliaKostynska/hRSpp3pwAVT4bckWxYYPtQor95Li2QMCklfkmSW5.jpg	f	2023-11-19 22:21:17	2023-11-19 22:21:17
9	alexsinchukova	657053675	Alex	Sinchuk	/images/avatars/alexsinchukova/GGjr3PndgoZ14P1hGnL92nlp6CaUvm6wt5ZuYXSx.jpg	f	2023-11-19 23:01:24	2023-11-19 23:01:24
10	vvoldi	238146286	Volodymyr	\N	/images/avatars/avatar.jpeg	t	2023-11-20 00:53:42	2023-11-22 13:30:50
21	Ola_Suprun	338941307	Оля		/images/avatars/avatar.jpeg	f	2023-11-22 22:25:23	2023-11-22 22:25:23
22	Volodya_s	575970507	Volodya		/images/avatars/Volodya_s/Ko53GTs9ubAe4WgKg01PtlrIsz1CvjR6Yu0f36R7.jpg	f	2023-11-22 22:56:55	2023-11-22 22:56:55
23	Hhiiraaeethh	536123935	Friday	Melancholy	/images/avatars/Hhiiraaeethh/UH8uDL1RLJbaEcBJXNYXPjimpgyAJgFBaGK1YfTj.jpg	f	2023-11-23 08:51:02	2023-11-23 08:51:02
24	ilololololol	5332502607	Ilona 🇺🇦		/images/avatars/ilololololol/R8CsL0KjbF3t81j9ETjRnVlJDQgT10xusYD5b7gN.jpg	f	2023-11-23 20:24:31	2023-11-23 20:24:31
25	thenetlogs	199045763	Serhii	M	/images/avatars/avatar.jpeg	f	2023-11-23 22:05:42	2023-11-23 22:05:42
26	she_elena	651702253	Олена		/images/avatars/she_elena/Ij3qA0hTglirruT2gOEJCAHWryK2xSei1bSRUuxi.jpg	f	2023-11-23 22:19:42	2023-11-23 22:19:42
11	Alkantel	584250935	Alex		/images/avatars/avatar.jpeg	f	2023-11-20 11:24:09	2023-11-20 11:24:09
12	Guru_b_o	414923557	Майор с.ц.з.		/images/avatars/Guru_b_o/l3TrAbrxbkgJjBGR7t36Esk7GjuUQPnGalrOYnEi.jpg	t	2023-11-20 13:26:30	2023-11-20 13:26:30
3	setnemo	281861745	Artem	Pakhomov	/images/avatars/setnemo/photo_2023-11-20_01-20-48.jpg	t	2023-11-17 22:35:23	2023-11-20 13:37:08
13	vo1dee	15671125	Maxim	Agarkov	/images/avatars/avatar.jpeg	f	2023-11-20 15:12:20	2023-11-20 15:12:20
14	Vedmezha_Brunatne_ta_Puhnaste	257150913	Vedmezha		/images/avatars/Vedmezha_Brunatne_ta_Puhnaste/QQ12Revoxn5FQB2iaLL8T93s8OXcM0A0gEBNoYH0.jpg	f	2023-11-20 15:55:42	2023-11-20 15:55:42
15	rnaumenk	351197193	Ruslan	Naumenko	/images/avatars/rnaumenk/hdvJHM9D6kYnGSm2gCpDVznLet9xOOqonCEUj3HR.jpg	t	2023-11-20 16:07:54	2023-11-20 16:07:54
28	nklymok	346797883	Naz		/images/avatars/nklymok/34DvKJmKDzmzZSv9vdD5tUroxxWNEm48MDMUX59k.jpg	f	2023-11-26 23:09:12	2023-11-26 23:09:12
27	GoldSquealing	5268164442	Anastasia	Vodianko	/images/avatars/GoldSquealing/12345.png	f	2023-11-26 15:48:23	2023-11-30 16:15:11
41	kotykvatopal	1726149461	ЛютаЯ	Квітка	/images/avatars/avatar.jpeg	t	2023-11-30 23:33:34	2023-11-30 23:33:34
42	dm_alexandr	432906433	Александр		/images/avatars/dm_alexandr/sQg4D3Ze46pAxObaFii9bvS42Kmrzb8vkTNJwa9b.jpg	f	2023-12-05 02:13:34	2023-12-05 02:13:34
43	wirdlessr	474709911	Lazy	Rat	/images/avatars/avatar.jpeg	f	2023-12-06 09:46:56	2023-12-06 09:46:56
44	ebgun	74282500	Eugene		/images/avatars/avatar.jpeg	f	2023-12-06 18:52:48	2023-12-06 18:52:48
45	planespotter325	518545865	Spotter Oleh	['Зрозтер містік'] ✙	/images/avatars/planespotter325/x7B6jxoFNln9iUIyYKVZosVYp6sDHFkw1u389EhC.jpg	t	2023-12-07 16:18:45	2023-12-07 16:18:45
1	admin	5609509050	Серійний донатер	\N	/images/avatars/admin/ukrainian-waving-flag-4.gif	t	2023-11-17 19:44:44	2023-11-27 00:35:49
2	awesome_freak	450937864	Анюта🦕😻☕️	\N	/images/avatars/awesome_freak/20160703_181416.jpg	t	2023-11-17 21:36:29	2023-11-27 00:36:11
29	Oleksa_01	416845716	Олександр		/images/avatars/avatar.jpeg	f	2023-11-27 10:17:34	2023-11-27 10:17:34
30	mkurchin	329416571	Max		/images/avatars/avatar.jpeg	f	2023-11-27 11:04:36	2023-11-27 11:04:36
31	khoangren	414919185	Рен		/images/avatars/avatar.jpeg	f	2023-11-28 11:49:35	2023-11-28 11:49:35
32	ta_sama_la	5200209684	Світлана		/images/avatars/ta_sama_la/37Qof1y2jZpnA1z283zmKzoU9coCeaOHrMKySptt.jpg	f	2023-11-28 12:17:34	2023-11-28 12:17:34
33	MaxBiliaievskyi	530562426	Max	Biliaievskyi	/images/avatars/MaxBiliaievskyi/Ava2.jpg	f	2023-11-28 12:32:12	2023-11-28 12:36:49
34	ViktorKozlov123	405536490	Viktor	Kozlov	/images/avatars/avatar.jpeg	f	2023-11-28 13:06:03	2023-11-28 13:06:03
35	Artificial_friend	1614192868	Oleksii	M	/images/avatars/avatar.jpeg	f	2023-11-28 14:35:29	2023-11-28 14:35:29
36	AquaNational	1113268621	МИКОЛА		/images/avatars/AquaNational/kw8Lua47x9LfO0qRdBnyrwS8jxurDuX4U80pUK2l.jpg	f	2023-11-28 19:41:50	2023-11-28 19:41:50
37	Olha_D	264593327	Оля		/images/avatars/avatar.jpeg	f	2023-11-28 23:05:49	2023-11-28 23:05:49
38	drunkmoody	387402850	drunkmoody		/images/avatars/drunkmoody/M9TsfhEd6G663jwy9BRLd6c5M4VpPjyeGC8755p2.jpg	f	2023-11-28 23:19:56	2023-11-28 23:19:56
39	neuroprosthetist	83733483	Aліса 🍉		/images/avatars/neuroprosthetist/ZIgvhQuiOnksli41vZ10jKzLSvZGVOAPzH1QVJLv.jpg	f	2023-11-29 19:01:34	2023-11-29 19:01:34
40	kzncvaa	382348359	Arina		/images/avatars/kzncvaa/IFAuMKbPPXMDrrOK9o31O1B8PkKYfqAy0uWObpXh.jpg	f	2023-11-29 22:14:29	2023-11-29 22:14:29
46	anton_demanov	80449302	Anton	Demanov	/images/avatars/anton_demanov/j34ri0RDoa7jCDq7Sq9zL8RDMeOjiuLEgGDR2IGi.jpg	f	2023-12-08 07:50:24	2023-12-08 07:50:24
47	roman_null	447833221	Roman		/images/avatars/roman_null/DKAnCZtuqiT8uagjhB22AP65uSynkAJJTgT8tfpq.jpg	f	2023-12-08 23:55:01	2023-12-08 23:55:01
48	KatarynaNikolaieva	1671150853	Катерина	Ніколаєва	/images/avatars/KatarynaNikolaieva/CEgqqHCpeJS7yrHq4VmBNIDZJkVL5Xg9JTbxtodZ.jpg	f	2023-12-09 09:04:36	2023-12-09 09:04:36
49	ChocolateCoherent	913263464	Inna		/images/avatars/ChocolateCoherent/wkUekwNdueRiwtByIExadUKbzSSFv2qpAnzA0L1F.jpg	f	2023-12-09 10:11:48	2023-12-09 10:11:48
50	dubysko	508126137	✙gvintikover✙		/images/avatars/dubysko/ePjMmJ4NxEzGp9xpb8Yaf5Vb7LVt14oSyI9hiiYE.jpg	t	2023-12-09 13:36:31	2023-12-09 13:36:31
51	BlueRomantic	562637924	Fluffy snowflake		/images/avatars/avatar.jpeg	f	2023-12-09 20:34:49	2023-12-09 20:34:49
52	VladBabienko	1064222	Владислав	Бабієнко	/images/avatars/avatar.jpeg	f	2023-12-09 20:42:59	2023-12-09 20:42:59
53	Sergio_melko	351683408	Sergio	Meleshko	/images/avatars/avatar.jpeg	f	2023-12-09 20:43:13	2023-12-09 20:43:13
54	taras_yarosh	482444467	taras	yarosh	/images/avatars/taras_yarosh/KfugK7nu9Qyf1KzO5adsKic95zXIGckPDeGRswVf.jpg	f	2023-12-09 20:57:16	2023-12-09 20:57:16
55	ata_zh	5625557031	Віра		/images/avatars/ata_zh/H5TDMvdXAL1f2zFxdX6FmgNUDx2HfqGJnwZxpUOQ.jpg	f	2023-12-09 21:50:32	2023-12-09 21:50:32
56	AnnaEshka	787190107	Anna		/images/avatars/avatar.jpeg	f	2023-12-10 00:06:14	2023-12-10 00:06:14
57	liza4nt	673248664	Liza		/images/avatars/avatar.jpeg	f	2023-12-12 15:16:33	2023-12-12 15:16:33
58	maruna21	213997837	Marina	Marina	/images/avatars/avatar.jpeg	f	2023-12-12 22:29:51	2023-12-12 22:29:51
59	reedsaadcaat	866710703	руда		/images/avatars/reedsaadcaat/B4GOPIoVxQX5eiNOrRdLigUx8IpfnEXOLzMOxpx6.jpg	f	2023-12-13 15:49:50	2023-12-13 15:49:50
60	bondar	501689	Andrew	B	/images/avatars/bondar/T032LEUNW4Q-U031WN5RYV8-44a324a0fa56-512.jpeg	t	2023-12-14 12:40:05	2023-12-14 12:45:51
61	ScarletFlying	933659107	Kateryna		/images/avatars/ScarletFlying/r04VLmeM6dLXahADsLK8aEWXEadfDRukxci2tWiC.jpg	f	2023-12-15 22:21:08	2023-12-15 22:21:08
62	helgaev	320470780	Oľa		/images/avatars/helgaev/Us5I8cENorHnwwhI9xvdbWqBDt4NduY5xsgICXba.jpg	f	2023-12-16 15:04:44	2023-12-16 15:04:44
63	pumpcin	575971298	Jan	Łukaszewicz	/images/avatars/pumpcin/bQNgdgrEPgV87VxOpCNhLwsz5IOXzeXAD20MC2P5.jpg	f	2023-12-16 17:32:17	2023-12-16 17:32:17
64	vinternatt09	476424321	Yuriy		/images/avatars/vinternatt09/cHKPuGDVDymrO76nWaMXtAh2DRTqNSUwYZ2K2yOh.jpg	f	2023-12-17 21:32:38	2023-12-17 21:32:38
65	a_bekkerel	404188035	a.bekkerel		/images/avatars/a_bekkerel/Won8F5z2chXbFoLVb1rwYiP1NByuzvDyEjKMGG6n.jpg	f	2023-12-19 01:09:15	2023-12-19 01:09:15
66	Aliceinfem	542377188	Аля		/images/avatars/Aliceinfem/QSeLQNKNOaL7byPIs1wwAcT5YqB2Q1OSDn5yBRMa.jpg	f	2023-12-19 23:25:28	2023-12-19 23:25:28
67	orangezms	353063328	Kateryna		/images/avatars/orangezms/ZHLKlceOTqo4KACPDhj2CetrOFXrqCny4YHddjcg.jpg	f	2023-12-25 20:54:28	2023-12-25 20:54:28
68	MuhaKartoha	220737009	Muha	Cartoha	/images/avatars/MuhaKartoha/ulJaXaN82wWgXVs36PBwtlATw3GPDUOCx8ojE6Cj.jpg	f	2023-12-25 20:57:57	2023-12-25 20:57:57
69	o_pyv	349162512	Oleksii	Pyvovarov	/images/avatars/avatar.jpeg	f	2023-12-26 10:14:34	2023-12-26 10:14:34
70	timurvlasnyk	157078964	T🇺🇦	V🇺🇦	/images/avatars/timurvlasnyk/asvkz36WwOgCmJ9h8Cq4EOdej4gXxC1Pqa841FUD.jpg	f	2023-12-26 19:33:41	2023-12-26 19:33:41
71	sera_nikulin	392339436	SERA	Nikulin	/images/avatars/sera_nikulin/ixy6qY1ICHe6FiEy5Hx8UlIENKlUcQnRaZo2Pd5R.jpg	t	2023-12-26 21:41:13	2023-12-26 21:41:13
82	elle_113	367714755	Еля	Слободяник	/images/avatars/avatar.jpeg	f	2024-01-05 18:45:49	2024-01-05 18:45:49
81	terminal	132874329	\N	\N	/images/avatars/x_compiler/ezgif-1-1aee3c1e22.png	f	2024-01-05 00:14:21	2024-01-05 00:44:19
80	ScarletLucky	329144984	max		/images/avatars/avatar.jpeg	f	2024-01-04 11:56:37	2024-01-04 11:56:37
79	GoldLegislative	300903736	Костя		/images/avatars/avatar.jpeg	f	2024-01-04 11:36:29	2024-01-04 11:36:29
78	ta_sama_Omelchenko	466843004	Yuliia	Oliinyk	/images/avatars/ta_sama_Omelchenko/C3NORN0VL1yG1ZHj80ZFA47sg6u5zcrQyRJOVabW.jpg	f	2024-01-01 11:13:22	2024-01-01 11:13:22
77	kate_kebab	473349699	Катерина	Олександрівна	/images/avatars/avatar.jpeg	f	2023-12-31 14:00:22	2023-12-31 14:00:22
76	spacemonkey17	212777530	Микита	🐳	/images/avatars/avatar.jpeg	f	2023-12-31 11:06:45	2023-12-31 11:06:45
75	grm12865	880281442	Olesia		/images/avatars/grm12865/AjC4ZqCDE5an68kYbs73p2Kmogm5WdDZDLg47QRQ.jpg	f	2023-12-31 09:35:09	2023-12-31 09:35:09
74	ephel_duath	315473289	Anna	Shyrshova	/images/avatars/ephel_duath/mV3lO6ClCprasOz4jcfvPCmYT6N1yUMQCmUMwuFP.jpg	f	2023-12-31 00:33:11	2023-12-31 00:33:11
73	MAV_1312	874265345	Пані Анна		/images/avatars/MAV_1312/46twlbl8S9eXqClcjlLr3g0K1w4VQAUgAv7kUC5t.jpg	f	2023-12-30 22:08:52	2023-12-30 22:08:52
72	GrayAccepted	1000513642	Mykola	Klymentovych	/images/avatars/GrayAccepted/IMG_2185.jpg	f	2023-12-27 19:05:12	2023-12-27 19:23:27
\.


--
-- Name: donates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.donates_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.migrations_id_seq', 57, true);


--
-- Name: oauth_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.oauth_clients_id_seq', 1, false);


--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.oauth_personal_access_clients_id_seq', 1, false);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- Name: user_codes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.user_codes_id_seq', 72, true);


--
-- Name: user_links_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.user_links_id_seq', 25, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.users_id_seq', 72, true);


--
-- Name: volunteers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.volunteers_id_seq', 15, true);


--
-- Name: donates donates_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.donates
    ADD CONSTRAINT donates_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: oauth_access_tokens oauth_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_access_tokens
    ADD CONSTRAINT oauth_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: oauth_auth_codes oauth_auth_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_auth_codes
    ADD CONSTRAINT oauth_auth_codes_pkey PRIMARY KEY (id);


--
-- Name: oauth_clients oauth_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_clients
    ADD CONSTRAINT oauth_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_personal_access_clients oauth_personal_access_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_personal_access_clients
    ADD CONSTRAINT oauth_personal_access_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_refresh_tokens oauth_refresh_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_refresh_tokens
    ADD CONSTRAINT oauth_refresh_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: user_codes user_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_codes
    ADD CONSTRAINT user_codes_pkey PRIMARY KEY (id);


--
-- Name: user_links user_links_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_links
    ADD CONSTRAINT user_links_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_telegram_id_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_telegram_id_unique UNIQUE (telegram_id);


--
-- Name: fundraisings volunteers_key_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.fundraisings
    ADD CONSTRAINT volunteers_key_unique UNIQUE (key);


--
-- Name: fundraisings volunteers_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.fundraisings
    ADD CONSTRAINT volunteers_pkey PRIMARY KEY (id);


--
-- Name: donates_hash_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX donates_hash_index ON public.donates USING btree (hash);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: oauth_access_tokens_user_id_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX oauth_access_tokens_user_id_index ON public.oauth_access_tokens USING btree (user_id);


--
-- Name: oauth_auth_codes_user_id_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX oauth_auth_codes_user_id_index ON public.oauth_auth_codes USING btree (user_id);


--
-- Name: oauth_clients_user_id_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX oauth_clients_user_id_index ON public.oauth_clients USING btree (user_id);


--
-- Name: oauth_refresh_tokens_access_token_id_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX oauth_refresh_tokens_access_token_id_index ON public.oauth_refresh_tokens USING btree (access_token_id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: user_codes_hash_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX user_codes_hash_index ON public.user_codes USING btree (hash);


--
-- Name: user_codes_user_id_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX user_codes_user_id_index ON public.user_codes USING btree (user_id);


--
-- Name: user_links_user_id_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX user_links_user_id_index ON public.user_links USING btree (user_id);


--
-- Name: users_first_name_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX users_first_name_index ON public.users USING btree (first_name);


--
-- Name: users_last_name_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX users_last_name_index ON public.users USING btree (last_name);


--
-- Name: users_username_index; Type: INDEX; Schema: public; Owner: docker
--

CREATE INDEX users_username_index ON public.users USING btree (username);


--
-- Name: DATABASE docker; Type: ACL; Schema: -; Owner: docker
--

REVOKE ALL ON DATABASE docker FROM PUBLIC;
REVOKE ALL ON DATABASE docker FROM docker;
GRANT ALL ON DATABASE docker TO docker;
GRANT CONNECT,TEMPORARY ON DATABASE docker TO PUBLIC;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: docker
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM docker;
GRANT ALL ON SCHEMA public TO docker;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

