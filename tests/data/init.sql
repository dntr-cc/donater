
CREATE USER docker;
CREATE DATABASE docker;
GRANT ALL PRIVILEGES ON DATABASE docker TO docker;
CREATE DATABASE root;
CREATE USER root;
GRANT ALL PRIVILEGES ON DATABASE root TO root;

--
-- Name: donates; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.donates (
                                id bigint NOT NULL,
                                user_id bigint NOT NULL,
                                fundraising_id bigint NOT NULL,
                                amount double precision DEFAULT '0'::double precision NOT NULL,
                                uniq_hash character varying(255) NOT NULL,
                                validated_at timestamp(0) without time zone,
                                deleted_at timestamp(0) without time zone,
                                created_at timestamp(0) without time zone,
                                updated_at timestamp(0) without time zone
);



--
-- Name: donates_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.donates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



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



--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


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



--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



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



--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.migrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



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



--
-- Name: oauth_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.oauth_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



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



--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.oauth_personal_access_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



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



--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.password_resets (
                                        email character varying(255) NOT NULL,
                                        token character varying(255) NOT NULL,
                                        created_at timestamp(0) without time zone
);



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



--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


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



--
-- Name: user_links_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.user_links_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



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



--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: volunteers; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.volunteers (
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



--
-- Name: volunteers_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.volunteers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: volunteers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.volunteers_id_seq OWNED BY public.volunteers.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.donates ALTER COLUMN id SET DEFAULT nextval('public.donates_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_clients ALTER COLUMN id SET DEFAULT nextval('public.oauth_clients_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_personal_access_clients ALTER COLUMN id SET DEFAULT nextval('public.oauth_personal_access_clients_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_links ALTER COLUMN id SET DEFAULT nextval('public.user_links_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.volunteers ALTER COLUMN id SET DEFAULT nextval('public.volunteers_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: docker
--


--
-- Data for Name: donates; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.donates (id, user_id, fundraising_id, amount, uniq_hash, validated_at, deleted_at, created_at, updated_at) FROM stdin;
1	1	1	0	655925323dfad	2023-11-19 03:43:01	\N	2023-11-18 22:57:58	2023-11-19 03:43:01
34	18	6	0	655cc9bebccbe	2023-11-22 16:46:02	\N	2023-11-21 17:17:49	2023-11-22 16:46:02
65	7	1	0	65625b4f7d0f1	\N	\N	2023-11-25 22:38:58	2023-11-25 22:38:58
62	13	8	50	6561e693bd8d9	2023-11-27 19:52:06	\N	2023-11-25 14:21:58	2023-12-04 00:36:29
58	7	5	50	6560f85fb31f9	2023-11-27 10:31:04	\N	2023-11-24 21:25:25	2023-12-04 00:37:09
56	9	8	1	65609ef76f69a	2023-11-25 16:42:03	\N	2023-11-24 15:03:28	2023-12-04 00:37:10
67	1	8	111	656298ad8ce26	2023-11-27 19:52:06	\N	2023-11-26 03:24:39	2023-12-04 00:36:26
68	11	1	10	6562ec0416602	2023-11-26 18:41:01	\N	2023-11-26 09:00:08	2023-12-04 00:36:26
64	9	5	1	65621462b74a8	2023-11-27 10:32:03	\N	2023-11-25 17:36:24	2023-12-04 00:36:28
59	13	6	101	6561e648f1fb6	2023-11-26 17:47:05	\N	2023-11-25 14:20:11	2023-12-04 00:36:30
57	9	6	1	65609f222c5f9	2023-11-26 17:47:05	\N	2023-11-24 15:04:01	2023-12-04 00:37:09
53	26	8	10	655fb415c9eeb	2023-11-25 16:40:04	\N	2023-11-23 22:21:37	2023-12-04 00:37:12
63	9	6	1	65621435bf114	2023-11-26 17:47:04	\N	2023-11-25 17:36:00	2023-12-04 00:36:29
70	1	5	111	6563a238e3637	2023-11-27 10:28:04	\N	2023-11-26 21:54:33	2023-12-04 00:36:25
66	1	5	111	6562985b978d5	2023-11-27 10:29:03	\N	2023-11-26 02:59:53	2023-12-04 00:36:27
60	13	5	50	6561e3426e9fb	2023-11-27 10:31:04	\N	2023-11-25 14:21:49	2023-12-04 00:36:30
71	1	8	111	6563a2813f771	2023-11-27 19:52:05	\N	2023-11-26 21:55:15	2023-12-04 00:36:24
47	22	8	20	655e6b2376038	2023-11-23 14:12:05	\N	2023-11-22 22:57:39	2023-12-04 00:37:17
72	28	3	100	6563b3f889000	2023-11-30 20:33:06	\N	2023-11-26 23:17:43	2023-12-04 00:36:24
55	9	5	1	65609ed62f10c	2023-11-27 10:31:05	\N	2023-11-24 15:02:45	2023-12-04 00:37:11
52	7	5	50	655fa21055daf	2023-11-24 09:26:02	\N	2023-11-23 21:04:31	2023-12-04 00:37:13
51	1	8	111	655f88f8e83ae	2023-11-25 16:42:04	\N	2023-11-23 19:17:26	2023-12-04 00:37:13
50	9	8	3	655f3f821f334	2023-11-25 16:39:04	\N	2023-11-23 14:04:27	2023-12-04 00:37:14
49	21	8	50	655e63a398b99	2023-11-23 14:12:03	\N	2023-11-23 13:34:26	2023-12-04 00:37:15
48	1	7	111	655e6dcf09a41	2023-11-30 20:35:05	\N	2023-11-22 23:09:32	2023-12-04 00:37:16
46	1	5	1	655e63ff9a190	2023-11-23 08:29:02	\N	2023-11-22 22:36:06	2023-12-04 00:37:17
45	1	8	1111	655e63bf7149f	2023-11-23 14:13:02	\N	2023-11-22 22:26:30	2023-12-04 00:37:18
44	1	5	111	655e18bb0bdf0	2023-11-23 08:28:04	\N	2023-11-22 17:06:11	2023-12-04 00:37:18
43	9	5	3	655d9c1bb94b9	2023-11-22 09:48:02	\N	2023-11-22 08:14:30	2023-12-04 00:37:19
42	1	5	111	655d49cc1ad16	2023-11-22 09:48:02	\N	2023-11-22 02:23:10	2023-12-04 00:37:19
41	1	5	111	655d4984de01f	2023-11-22 09:49:01	\N	2023-11-22 02:22:02	2023-12-04 00:37:19
40	1	5	111	655d49124f7c5	2023-11-22 09:49:02	\N	2023-11-22 02:20:16	2023-12-04 00:37:19
39	13	5	50	655d129ad5a03	2023-11-22 09:50:01	\N	2023-11-21 22:28:18	2023-12-04 00:37:20
38	19	5	200	655d0d9f03343	2023-11-22 09:39:03	\N	2023-11-21 22:07:08	2023-12-04 00:37:20
37	7	5	50	655d0d532599e	2023-11-22 09:38:03	\N	2023-11-21 22:06:14	2023-12-04 00:37:21
36	20	5	10	655d0d6498b54	2023-11-22 09:38:03	\N	2023-11-21 22:06:05	2023-12-04 00:37:21
35	1	5	111	655cf6db8c2c3	2023-11-22 09:37:03	\N	2023-11-21 20:29:23	2023-12-04 00:37:22
33	1	5	111	655cb65c0e591	2023-11-22 09:36:04	\N	2023-11-21 15:54:26	2023-12-04 00:37:23
32	1	6	612.259999999999991	655cb5601b554	2023-11-22 16:45:03	\N	2023-11-21 15:51:14	2023-12-04 00:37:23
31	3	5	111	655c91ebeb1bb	2023-11-22 09:35:06	\N	2023-11-21 13:18:53	2023-12-04 00:37:23
30	16	5	1	655c8d722efc0	2023-11-22 13:27:02	\N	2023-11-21 12:59:51	2023-12-04 00:37:24
29	7	5	50	655c890c43751	2023-11-22 09:35:07	\N	2023-11-21 12:42:10	2023-12-04 00:37:24
28	9	5	2	655c830e11101	2023-11-22 09:34:06	\N	2023-11-21 12:15:26	2023-12-04 00:37:25
27	1	4	111	655bdecb4e015	2023-11-21 12:10:02	\N	2023-11-21 00:34:46	2023-12-04 00:37:26
26	2	3	33	655bc6ea46ee2	2023-11-21 12:11:01	\N	2023-11-20 22:52:34	2023-12-04 00:37:26
25	2	5	33	655bc6b1898e6	2023-11-22 09:25:05	\N	2023-11-20 22:51:54	2023-12-04 00:38:09
24	1	5	111	655bc5e8be555	2023-11-22 09:16:06	\N	2023-11-20 22:48:40	2023-12-04 13:15:22
23	1	5	111	655b7e1996556	2023-11-22 09:16:07	\N	2023-11-20 17:42:16	2023-12-04 13:15:24
22	14	5	1	655b655ec5ef5	2023-11-22 13:26:02	\N	2023-11-20 15:59:00	2023-12-04 13:15:24
21	13	5	75	655b636a634f4	2023-11-22 09:22:07	\N	2023-11-20 15:48:02	2023-12-04 13:15:25
20	7	5	50	655b50f6e9186	2023-11-22 09:16:08	\N	2023-11-20 14:29:55	2023-12-04 13:15:25
19	12	3	10	655b473f08897	2023-11-21 12:11:03	\N	2023-11-20 13:47:51	2023-12-04 13:15:26
18	12	2	10	655b471893201	2023-11-21 12:11:04	\N	2023-11-20 13:47:10	2023-12-04 13:15:27
17	12	1	10	655b46ece844d	2023-11-21 12:12:03	\N	2023-11-20 13:46:32	2023-12-04 13:15:27
16	12	5	10	655b46c0d702f	2023-11-22 09:17:07	\N	2023-11-20 13:45:48	2023-12-04 13:15:28
15	12	5	100	655b42c9e61e0	2023-11-22 13:31:03	\N	2023-11-20 13:28:49	2023-12-04 13:15:28
14	12	5	100	655b426686de2	2023-11-22 09:25:06	\N	2023-11-20 13:28:09	2023-12-04 13:15:29
13	9	3	5	655af5dcb4b28	2023-11-20 13:13:02	\N	2023-11-20 08:02:03	2023-12-04 13:15:29
12	10	5	5.08000000000000007	655a92da1add6	2023-11-20 10:29:03	\N	2023-11-20 00:58:01	2023-12-04 13:15:30
11	10	5	1	655a92b942dbe	2023-11-20 10:29:03	\N	2023-11-20 00:57:29	2023-12-04 13:15:31
10	10	5	1	655a925a7e6d9	2023-11-20 10:29:04	\N	2023-11-20 00:56:06	2023-12-04 13:15:31
9	10	5	1	655a9233b4055	2023-11-20 10:28:03	\N	2023-11-20 00:55:22	2023-12-04 13:15:32
8	10	5	1	655a91f66ded0	2023-11-22 13:28:03	\N	2023-11-20 00:54:43	2023-12-04 13:15:33
7	1	5	111	655a8e77b9dfe	2023-11-20 10:27:03	\N	2023-11-20 00:39:44	2023-12-04 13:15:33
6	7	2	50	655a28b56f3f4	2023-11-19 19:08:02	\N	2023-11-19 17:27:17	2023-12-04 13:15:34
5	3	4	111	655973f92c934	2023-11-21 12:10:06	\N	2023-11-19 04:34:08	2023-12-04 13:15:34
4	3	2	111	655973c402ce3	2023-11-19 19:09:03	\N	2023-11-19 04:33:22	2023-12-04 13:15:35
3	3	1	111	6559734016d1c	2023-11-19 13:58:03	\N	2023-11-19 04:32:26	2023-12-04 13:15:36
2	3	3	111	655972dce2257	2023-11-20 13:13:03	\N	2023-11-19 04:29:36	2023-12-04 13:15:36
99	9	4	1	65646a27898262.98398604	2023-11-30 20:34:05	\N	2023-11-27 12:06:48	2023-12-04 00:36:21
98	9	3	1	656469e28a0296.05032767	2023-11-30 20:33:05	\N	2023-11-27 12:05:41	2023-12-04 00:36:22
105	31	10	0	6565b86553d382.45368985	\N	\N	2023-11-28 11:52:52	2023-11-28 11:52:52
106	31	10	0	6565bb26a57994.14553137	\N	\N	2023-11-28 12:05:03	2023-11-28 12:05:03
108	33	10	0	6565c1acea0362.22725500	\N	\N	2023-11-28 12:36:29	2023-11-28 12:36:29
113	36	10	0	6566265f22fb26.50412947	\N	\N	2023-11-28 19:43:36	2023-11-28 19:43:36
97	9	8	1	656469a9d58b29.48003005	2023-11-27 19:52:03	\N	2023-11-27 12:04:43	2023-12-04 00:36:22
96	9	5	1	6564698ba739b8.91237837	2023-11-30 12:06:09	\N	2023-11-27 12:04:17	2023-12-04 00:36:22
90	29	5	17	6564509e9c34c	2023-11-27 10:28:02	\N	2023-11-27 10:18:58	2023-12-04 00:36:23
89	3	8	111	6563f602baa48	2023-11-27 19:52:04	\N	2023-11-27 03:51:28	2023-12-04 00:36:23
88	3	5	111	6563f5b5e4293	2023-11-27 10:28:03	\N	2023-11-27 03:50:30	2023-12-04 00:36:24
54	25	5	10	655fb41e1be0e	2023-11-27 02:52:06	\N	2023-11-23 22:24:02	2023-12-04 00:37:12
120	1	10	111	65675bc95af215.40447781	2023-12-04 22:38:02	\N	2023-11-29 17:42:35	2023-12-04 22:38:08
117	7	10	50	6566bcc5cb1a40.83789456	2023-12-04 22:38:03	\N	2023-11-29 06:24:27	2023-12-04 22:38:09
115	13	10	57	65665588f1eff4.00130112	2023-12-04 22:38:04	\N	2023-11-28 23:04:41	2023-12-04 22:38:09
114	1	10	111	6566546b38f513.55055746	2023-12-04 22:38:04	\N	2023-11-28 22:58:50	2023-12-04 22:38:09
132	1	8	111	6568ddf2ed10e4.83189405	2023-12-02 13:11:02	\N	2023-11-30 21:10:32	2023-12-04 00:36:09
131	24	8	20	65679e2de090c7.67654695	2023-11-30 16:45:03	\N	2023-11-30 16:31:57	2023-12-04 00:36:09
109	1	10	111	6565c68c2ae7f3.66962952	2023-12-04 22:38:05	\N	2023-11-28 12:53:38	2023-12-04 22:38:10
107	32	10	100	6565be9137d015.23642914	2023-12-04 22:38:05	\N	2023-11-28 12:19:45	2023-12-04 22:38:10
130	18	8	100	656741b3b7f408.16538817	2023-11-30 16:45:04	\N	2023-11-30 16:28:36	2023-12-04 00:36:10
137	41	8	0	65679c89384c28.17976775	2023-12-03 14:01:02	2023-12-03 16:59:44	2023-12-03 14:00:58	2023-12-03 16:59:44
136	1	8	111	656b0e8a9d67d0.89225533	2023-12-03 13:32:02	\N	2023-12-02 13:02:24	2023-12-04 00:36:07
135	1	8	111	6569a182b18c63.34813729	2023-12-02 13:12:02	\N	2023-12-01 11:04:48	2023-12-04 00:36:07
128	24	8	20	6567a04a40c760.76121848	2023-11-30 16:45:05	\N	2023-11-29 22:34:54	2023-12-04 00:36:11
127	1	8	111	65679eaea90320.57247586	2023-11-30 16:45:06	\N	2023-11-29 22:30:20	2023-12-04 00:36:12
126	7	8	20.6000000000000014	65679e95b66cb7.26371958	2023-11-30 17:02:02	\N	2023-11-29 22:28:02	2023-12-04 00:36:12
125	40	8	70	65679c0c38ef77.33782785	2023-11-30 16:45:07	\N	2023-11-29 22:17:55	2023-12-04 00:36:12
124	19	8	200	65679b84c1b052.19293789	2023-11-30 17:02:03	\N	2023-11-29 22:14:29	2023-12-04 00:36:13
123	7	8	50	656790fa787869.33743920	2023-11-30 17:03:02	\N	2023-11-29 21:30:59	2023-12-04 00:36:13
122	13	8	100	65679117df73f5.04285875	2023-11-30 16:45:09	\N	2023-11-29 21:30:14	2023-12-04 00:36:13
121	39	8	200	65676e6e8fb8d1.64650806	2023-12-03 13:42:02	\N	2023-11-29 19:02:27	2023-12-04 00:36:14
119	1	5	111	65675ba2628981.23519114	2023-11-30 12:07:05	\N	2023-11-29 17:41:49	2023-12-04 00:36:15
118	1	8	111	65675b6ab59a94.95233172	2023-11-30 16:45:11	\N	2023-11-29 17:41:05	2023-12-04 00:36:16
116	37	8	50	6566562de5fc83.12843621	2023-11-30 16:45:12	\N	2023-11-28 23:07:35	2023-12-04 00:36:16
112	16	8	1000	65661aea9fb245.89740899	2023-12-03 14:00:04	\N	2023-11-28 18:53:44	2023-12-04 00:36:17
111	1	5	111	656607a0427e87.53837856	2023-11-30 12:10:05	\N	2023-11-28 17:31:11	2023-12-04 00:36:18
110	1	8	111	6566075ed21253.18109544	2023-11-30 16:45:14	\N	2023-11-28 17:30:06	2023-12-04 00:36:18
102	30	8	100	65645c113630f7.52679678	2023-11-27 20:22:02	\N	2023-11-27 20:21:42	2023-12-04 00:36:19
101	1	5	111	656475241ed540.77174408	2023-11-30 12:06:07	\N	2023-11-27 12:53:58	2023-12-04 00:36:20
100	1	8	111	656474e56923a6.39450835	2023-11-27 19:52:02	\N	2023-11-27 12:53:10	2023-12-04 00:36:20
151	13	11	50	6570a6da5facf7.28542453	2023-12-07 19:35:02	\N	2023-12-06 18:53:10	2023-12-07 19:35:09
147	1	11	1111	6570a2884ec7d2.99202585	2023-12-07 19:31:03	\N	2023-12-06 18:35:11	2023-12-07 19:31:10
144	9	3	3	656eeeaba62861.93497869	2023-12-08 15:34:08	\N	2023-12-05 11:35:00	2023-12-08 15:34:18
153	18	11	150	6570aef62f5149.05967534	2023-12-07 19:33:02	\N	2023-12-06 19:29:43	2023-12-07 19:33:11
148	41	11	100	6570a388cacc05.68727855	2023-12-07 19:37:02	\N	2023-12-06 18:39:12	2023-12-07 19:37:07
152	13	11	50	6570a6fd7a9fc9.49682303	2023-12-07 19:35:02	\N	2023-12-06 18:53:42	2023-12-07 19:35:02
154	42	11	1000	65710167d21897.37267599	2023-12-07 19:48:01	\N	2023-12-07 01:21:59	2023-12-07 19:48:07
134	41	6	100	6569005daa0948.11000368	2023-12-08 15:34:10	\N	2023-11-30 23:37:57	2023-12-08 15:34:21
155	13	7	55	657202e1b77437.83149167	2023-12-08 15:34:05	\N	2023-12-07 19:38:10	2023-12-08 15:34:15
145	9	6	1	656eeecbdf52c8.71578152	2023-12-08 15:34:07	\N	2023-12-05 11:41:36	2023-12-08 15:34:18
133	41	3	200	6568ffaea1ee83.21826949	2023-12-08 15:34:11	\N	2023-11-30 23:35:30	2023-12-08 15:34:21
158	9	3	2	65731c7d7ad2b7.78935135	2023-12-10 15:55:13	\N	2023-12-08 15:39:29	2023-12-10 15:55:26
161	9	4	1	65731cd5c10e68.28834401	2023-12-08 15:45:02	\N	2023-12-08 15:41:00	2023-12-08 15:45:10
162	1	12	1111	657382286c17f7.49557034	2023-12-09 17:34:02	\N	2023-12-08 22:54:07	2023-12-09 17:34:08
163	50	12	69	6574516ec8f4e1.00105415	2023-12-09 17:34:02	\N	2023-12-09 13:40:39	2023-12-09 17:34:08
159	9	11	2	65731c97245c38.09051992	2023-12-14 23:17:19	\N	2023-12-08 15:39:55	2023-12-14 23:17:41
168	9	1	2.04999999999999982	6574a417e62873.30042658	2023-12-10 16:06:04	\N	2023-12-09 19:30:22	2023-12-10 16:06:04
165	9	3	2	6574a3d3b8fc50.87153928	2023-12-10 15:55:11	\N	2023-12-09 19:29:10	2023-12-10 15:55:23
156	46	11	500	6572aea0df45d5.37970667	2023-12-14 23:17:20	\N	2023-12-08 07:51:31	2023-12-14 23:17:42
157	9	10	2	65731c55974133.65680757	2023-12-20 10:33:09	\N	2023-12-08 15:38:54	2023-12-20 10:33:20
166	9	11	2	6574a3eb24fa43.95504034	2023-12-14 23:17:18	\N	2023-12-09 19:29:34	2023-12-14 23:17:40
149	41	10	20	6570a3fed38900.26274525	2023-12-20 10:33:09	\N	2023-12-06 18:41:08	2023-12-20 10:33:20
146	31	10	100	65702b4edf73b8.03043050	2023-12-20 10:33:10	\N	2023-12-06 10:06:17	2023-12-20 10:33:20
143	9	10	100	656eee75b0bf59.41105200	2023-12-20 10:33:10	\N	2023-12-05 11:34:29	2023-12-20 10:33:20
142	7	10	200	656e43ff3bf6b6.52495152	2023-12-20 10:33:10	\N	2023-12-04 23:27:32	2023-12-20 10:33:21
174	56	11	50	6574e52b95ad22.52885389	2023-12-14 23:17:17	\N	2023-12-10 00:09:09	2023-12-14 23:17:40
203	47	11	200	657390cae1cb88.57861440	2023-12-14 23:24:02	\N	2023-12-14 23:23:05	2023-12-14 23:24:02
172	13	4	69	6574a51a7ccd79.24636786	2023-12-10 15:55:05	\N	2023-12-09 19:34:50	2023-12-10 15:55:21
171	9	7	55	6574a47ad90a58.00155199	2023-12-10 15:55:06	\N	2023-12-09 19:31:59	2023-12-10 15:55:21
170	9	2	2	6574a44f601fc7.08264179	2023-12-10 15:55:07	\N	2023-12-09 19:31:33	2023-12-10 15:55:22
169	9	4	2.14000000000000012	6574a43260e664.37407912	2023-12-10 15:55:08	\N	2023-12-09 19:30:50	2023-12-10 15:55:22
167	9	6	2	6574a402199790.04926091	2023-12-10 15:55:09	\N	2023-12-09 19:29:55	2023-12-10 15:55:22
160	9	6	2	65731cb084f0a2.51919913	2023-12-08 15:46:01	\N	2023-12-08 15:40:18	2023-12-10 15:55:25
175	54	13	0	6575c31d2e8852.97781365	\N	\N	2023-12-10 15:55:42	2023-12-10 15:55:42
176	54	13	0	6575c2fdb22ba7.03906210	\N	\N	2023-12-10 15:59:17	2023-12-10 15:59:17
173	55	1	20	6574c508a91673.36906471	2023-12-10 16:05:04	\N	2023-12-09 21:52:11	2023-12-10 16:05:14
177	53	3	100	6575c695c54117.42920680	2023-12-10 16:11:02	\N	2023-12-10 16:10:48	2023-12-10 16:11:11
178	52	7	50	6575c728359837.09702775	2023-12-10 16:13:02	\N	2023-12-10 16:12:27	2023-12-10 16:13:12
197	60	11	0	65731097245с38.09051992	\N	\N	2023-12-14 22:02:02	2023-12-14 22:02:02
164	9	10	34	6574a3b76e76b1.48927411	2023-12-20 10:33:08	\N	2023-12-09 19:28:47	2023-12-20 10:33:19
207	1	14	111	657e259052e428.75093549	2023-12-19 17:05:04	\N	2023-12-17 00:33:17	2023-12-19 17:05:14
218	42	10	0	658224e3685309.98071422	\N	\N	2023-12-20 01:20:11	2023-12-20 01:20:11
195	60	6	100	657adde7cde6e7.76336773	2023-12-17 17:47:03	\N	2023-12-14 12:52:26	2023-12-17 17:47:03
191	9	6	2	6579a326b0bcf6.75724919	2023-12-17 17:47:04	\N	2023-12-13 14:27:40	2023-12-17 17:47:04
201	59	11	50	6579b66e88d1e2.55546917	2023-12-14 23:17:02	\N	2023-12-14 22:15:15	2023-12-14 23:17:26
192	59	11	100	6579b67ec1d602.67398614	2023-12-14 23:17:07	\N	2023-12-13 15:51:08	2023-12-14 23:17:30
190	9	11	2	6579a31270df37.82181918	2023-12-14 23:17:09	\N	2023-12-13 14:27:14	2023-12-14 23:17:32
188	58	11	20	6578c2bfe129a9.30039376	2023-12-14 23:17:10	\N	2023-12-12 22:31:20	2023-12-14 23:17:33
184	9	11	2	6578a23aa49de0.58934826	2023-12-14 23:17:12	\N	2023-12-12 20:11:24	2023-12-14 23:17:35
181	1	11	111	65785a801fdbc6.25824687	2023-12-14 23:17:14	\N	2023-12-12 15:05:33	2023-12-14 23:17:37
179	45	11	50	657707ec461f20.84925783	2023-12-14 23:17:16	\N	2023-12-11 15:01:05	2023-12-14 23:17:38
221	41	10	0	65872c60eaf9a5.03174244	\N	\N	2023-12-23 20:52:55	2023-12-23 20:52:55
186	9	6	1	6578a26b397ab9.32679530	2023-12-17 17:47:05	\N	2023-12-12 20:12:29	2023-12-17 17:47:05
185	9	6	1	6578a2521690d2.76336137	2023-12-17 17:47:06	\N	2023-12-12 20:11:50	2023-12-17 17:47:06
187	9	4	1	6578a292a2eca1.38660369	2023-12-17 17:47:05	\N	2023-12-12 20:13:01	2023-12-17 17:47:14
206	1	13	111	657e255fe169b9.31429984	2023-12-26 20:00:05	\N	2023-12-17 00:32:38	2023-12-26 20:00:16
223	1	13	333	658a0e18054b97.83134108	2023-12-26 19:55:02	\N	2023-12-26 01:20:21	2023-12-26 19:57:10
217	1	14	333	6581ab7ee6e271.31328376	2023-12-26 01:07:02	\N	2023-12-19 16:41:24	2023-12-26 01:07:09
204	63	7	50	657dc35d99ab25.81605781	2023-12-17 17:48:02	\N	2023-12-16 17:34:58	2023-12-17 17:48:10
196	1	1	333	657af1e861d3c1.84149064	2023-12-17 17:48:03	\N	2023-12-14 14:17:10	2023-12-17 17:48:10
194	60	1	100	657adc870b2f75.10531153	2023-12-17 17:48:03	\N	2023-12-14 12:45:37	2023-12-17 17:48:10
209	9	3	0	6580513800c417.29106445	\N	\N	2023-12-18 16:03:51	2023-12-18 16:03:51
210	9	1	0	6580514c88e0f6.12269704	\N	\N	2023-12-18 16:04:12	2023-12-18 16:04:12
211	9	4	0	65805161904a17.01225995	\N	\N	2023-12-18 16:05:44	2023-12-18 16:05:44
213	65	3	0	6580d1fdc95e93.77061186	\N	\N	2023-12-19 01:13:30	2023-12-19 01:13:30
216	1	10	0	6581ab5e6e64f1.51649806	\N	\N	2023-12-19 16:40:51	2023-12-19 16:40:51
212	65	14	1	6580d16722ffd8.18994109	2023-12-26 01:07:04	\N	2023-12-19 01:11:59	2023-12-26 01:07:11
219	42	13	500	658225488d8d26.58805594	2023-12-26 19:55:03	\N	2023-12-20 01:24:34	2023-12-26 19:57:11
208	9	10	3	65805120bcbac6.99979616	2023-12-20 10:33:04	\N	2023-12-18 16:03:30	2023-12-20 10:33:16
205	1	10	5	657e252e429c60.78022598	2023-12-20 10:33:05	\N	2023-12-17 00:31:44	2023-12-20 10:33:17
193	27	10	34	657a44ac2652e7.41623747	2023-12-20 10:33:06	\N	2023-12-14 02:01:54	2023-12-20 10:33:17
189	9	10	300	6579a2f6144f18.36029873	2023-12-20 10:33:06	\N	2023-12-13 14:26:52	2023-12-20 10:33:17
183	9	10	2	6578a216d52649.90831998	2023-12-20 10:33:07	\N	2023-12-12 20:11:01	2023-12-20 10:33:18
182	1	10	10	65785ab67ec5b4.85252037	2023-12-20 10:33:07	\N	2023-12-12 15:06:30	2023-12-20 10:33:18
220	42	14	500	6582269b297437.65426428	2023-12-26 01:09:02	\N	2023-12-20 01:26:47	2023-12-26 01:09:10
222	1	14	0	658a0d1417afb6.81985908	\N	\N	2023-12-26 01:16:05	2023-12-26 01:16:05
224	1	10	0	658a0e43643388.45893189	\N	\N	2023-12-26 01:21:04	2023-12-26 01:21:04
180	1	13	111	65785a4886c144.26256277	2023-12-26 20:00:07	\N	2023-12-12 15:04:49	2023-12-26 20:00:16
215	1	13	333	6581aaf774d1f9.93065386	2023-12-26 19:55:04	\N	2023-12-19 16:40:04	2023-12-26 19:57:12
214	13	13	50	65818cd80cca17.54013753	2023-12-26 19:55:05	\N	2023-12-19 14:30:47	2023-12-26 19:57:12
\.


--
-- Name: donates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.donates_id_seq', 224, true);


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


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
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.migrations_id_seq', 53, true);


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
-- Name: oauth_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.oauth_clients_id_seq', 1, false);


--
-- Data for Name: oauth_personal_access_clients; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.oauth_personal_access_clients (id, client_id, created_at, updated_at) FROM stdin;
\.


--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.oauth_personal_access_clients_id_seq', 1, false);


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
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


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
-- Name: user_links_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.user_links_id_seq', 25, true);


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
72	GrayAccepted	1000513642	Mykola	Klymentovych	/images/avatars/GrayAccepted/IMG_2185.jpg	f	2023-12-27 19:05:12	2023-12-27 19:23:27
\.


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.users_id_seq', 72, true);


--
-- Data for Name: volunteers; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.volunteers (id, key, name, link, page, description, spreadsheet_id, avatar, is_enabled, user_id, deleted_at, created_at, updated_at) FROM stdin;
5	glits04_1	Збір на зв'язок	https://send.monobank.ua/jar/6nfMdq4Wph	https://x.com/Gilts04/status/1730201985914184121?s=20	<p>Збор коштів на мережеве обладнання для роти звʼязку 80 окремого батальйону управління КМП та тепловізійного монокуляру взводу охорони того ж батальйону. Підрозділ займається забезпеченням звʼязку в Миколаївській та Херсонській областях.<br><br><strong>Розіграш павербанку BASEUS 10000mAh 22.5w 1 донат від 1грн, 1 код - 1 квиток.</strong></p>\n<p><strong>Переможиця - <a href="../../u/alexsinchukova">@alexsinchukova</a></strong></p>\n<p>Збір закрито на суммі в 26727,55 грн. у зв'язку з закритям потреб благодійником</p>\n<p>Кошти зі збору було витрачено на</p>\n<ul>\n<li>Вантажний акумулятор WESTA 6CT-140 Аз standard Pretty Powerful WST140/WPP140 2 штуки - 10770 грн.</li>\n<li>\n<p class="product__title-left product__title-collapsed ng-star-inserted">Автомобільний акумулятор Bosch 74Ah Єв (-/+) S4008 (680EN) (0 092 S40 080) 1 штука - 4103 грн.</p>\n</li>\n<li>\n<p class="product__title-left product__title-collapsed ng-star-inserted">&nbsp;Маршрутизатор MikroTik hAP ax2 (C52iG-5HaxD2HaxD-TC) 2 штуки - 6897 грн.</p>\n</li>\n<li>Залишок коштів на наступний збір - 4 957,55 грн.</li>\n</ul>	1l47ghNanVRl5Q4lIxhFsA13iMNXXcKxbCdD_OWNnGvs	/images/banners/glits04_1.png	f	6	\N	2023-11-26 17:08:46	2023-11-30 16:47:38
7	rusoriz	Русоріз пана Стерненко	https://send.monobank.ua/jar/9ekqtYDMca	https://twitter.com/sternenko	🫡 Спільнота пана Стерненко допомогли війську на понад 480 (!!!) мільйонів гривень!<br>\n<br>\nСаме такий є проміжний звіт за період з 1 квітня 2022 по 12 серпня 2023. <br>\nНасправді сума і кількість переданого майна вже більша. <br>\n<br>\nНа ці кошти ми закупили у армію:<br>\n- понад 6000 FPV-дронів<br>\n- майже 1000 інших БПЛА<br>\n- 110 тепловізорів<br>\n- 107 ПНБ<br>\n- 225 одиниць транспорту <br>\nТа багато-багато іншого!<br>\nЗокрема, 1 надводний дрон та вклали кошти у дрони дальнього радіусу дії😉<br>\n<br>\nТакож до цієї справи долучився і я, сума моїх донатів склала 2 658 984 грн. <br>\nАле ваш внесок — величезний!<br>\nВи дуже істотно допомогли фронту. <br>\n<br>\nДякую вам!<br>\n<br>\nВолонтерські картки: <br>\n4441114454997899 моно<br>\n5168745030910761 приват<br>	1UbzlN19o1ahNK-rmnDwB5pPkR1E-O5YiGy6MwMyyn38	/images/banners/rusoriz.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 17:39:50
1	savelife	Повернись живим	https://send.monobank.ua/jar/91w3asqDZt	https://savelife.in.ua/en/	«Повернись живим» — це благодійний фонд компетентної допомоги армії, а також громадська організація, яка займається аналітикою у секторі безпеки та оборони, реалізує проєкти з реабілітації ветеранів через спорт.	1YqwMthW7y5SXM059AuAogkmhDg7BVMextxPT3IuB9_s	/images/banners/savelife.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 17:54:03
2	prytulafoundation	Фонд Сергія Притули	https://send.monobank.ua/jar/4aqbQf23WR	https://prytulafoundation.org/	Благодійний фонд Сергія Притули опікується посиленням Сил Оборони України, а також допомогою цивільному населенню, яке постраждало від російської агресії.	1dKiA7w69uv5FaawrSEXg04eiQW1FafDr8vNTGBTKAok	/images/banners/prytulafoundation.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 17:54:06
3	hospitallers	Медичний батальйон "Госпітальєри"	https://send.monobank.ua/jar/4Mtimtorvu	https://www.hospitallers.life	“Госпітальєри”— добровольча організація парамедиків. Була заснована Яною Зінкевич на початку бойових дій в Україні у 2014 році. Тоді Росія анексувала Крим і розпочала бойові дії на сході країни.	1ZSPaWAdm4aW-ZBwrzdk5u-vQwSda_wigj6bVjrDelOk	/images/banners/hospitallers.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 17:54:10
4	letsseethevictory	Фонд "Побачимо Перемогу"	https://send.monobank.ua/jar/4TmC32mY17	https://thevictory.org.ua	<h2>Наша місія</h2>\n<p>Місія нашого Благодійного Фонду полягає в тому, щоб допомагати людям, які втратили зір під час війни. На жаль, пересадка очей неможлива, тому життя людей після такої травми змінюється радикально.</p>\n<p>Фонд займається пошуком необхідних програм реабілітації та сучасних технологій відновлення зору в Україні та за кордоном, а також впровадженням в Україні існуючих методик та технологій по відновленню зору та реабілітації незрячих.</p>\n<h3>Медична допомога</h3>\n<p>Забезпечуємо постраждалим фінансування для якісного лікування, реабілітації та придбання медичних засобів, полегшуючи повсякденне функціонування.</p>\n<h3>Психологічна підтримка</h3>\n<p>Надаємо професійну допомогу постраждалим у вирішенні емоційних труднощів, стресу та тривоги, що виникають внаслідок втрати зору.</p>\n<h3>Соціальна підтримка</h3>\n<p>Допомагаємо постраждалим інтегруватися в суспільство, забезпечуючи інформацію, освіту, професійну підготовку та підтримку в зайнятості.</p>\n<h3>Розвиток та адаптація технологій</h3>\n<p>Сприяємо впровадженню інноваційних технологій, які полегшують повсякденне життя, роботу та навчання людей з втратою зору.</p>\n<h3>Соціальне обговорення та освіта</h3>\n<p>Підвищуємо обізнаність суспільства про проблеми людей з втратою зору, просуваємо толерантність та боремося зі стигмою та дискримінацією.</p>\n<h3>Юридична допомога</h3>\n<p>Забезпечуємо людям з втратою зору юридичну підтримку та консультування, спрямовані на захист їх прав та інтересів. Допомагаємо з отриманням інвалідності, соціальними виплатами та доступом до потрібних послуг і пільг.</p>	1lB-CZLWPg--o5YMdNbvuokL1Gmv_YzzEwqCa17JZfpA	https://donater.com.ua/images/banners/admin/Logo_128_newcolors-01.png	t	3	\N	2023-11-26 17:08:46	2023-11-26 18:27:33
6	setnemo_twitter_subscribe	Збір Госпам від setnemo	https://send.monobank.ua/jar/3irfquacv1	https://twitter.com/setnemo/status/1721256589582049664	<p>Збір госпам в адміна. Донатить в банку в за кожного підписника в тві. Дедлайн - 25 грудня 2023. За донат в банку від 100грн - шанс виграти нічник Місяць. Всього буде розиграно 2 нічника, один за реєстрацію по посиланню після донату, другий по донатам з кодами з цього сайту.<br><br><strong>Розіграш 2 x Moon Lamp 20sm. 100грн донату - 1 квиток</strong></p>	1y7TY9Cuo48kvXA4V4WNKouR8UroF7E514Oc8aKifylI	https://donater.com.ua/images/banners/admin/Possters_All_Type-01.png	f	3	\N	2023-11-26 17:08:46	2023-12-14 20:52:44
10	korch_dlya_1_obrspn	Корч для 1 ОБрСпН	https://send.monobank.ua/jar/4b3GVZzmcM	https://x.com/shidne_bydlo/status/1726526572973498634?s=20	<p>Корчі не вічні, особливо коли підори їбашать їх з ПТУРа. До нас звернувся <a href="https://x.com/SillyNami" target="_blank" rel="noopener">@SillyNami</a> який воює за наші сраки у 1й ОБрСпП ім Івана Богуна. Корчу хана і треба інший. <a href="https://x.com/evhenkyzmenko" target="_blank" rel="noopener">@evhenkyzmenko</a> знайде тачку і пригонить її у Франик.</p>\n<p><strong>Розіграш від <a href="../u/admin" target="_blank" rel="noopener">Серійного Донатера</a>:</strong></p>\n<p><strong>1 квиток - 50 грн з кодом. 100грн донат - 2 квитка. 75грн + 25 грн = 2 квитка. І т.д.</strong></p>\n<p><strong>Призи: 3 (три) переможця буде обрано рандомно</strong></p>\n<hr>\n<ol>\n<li><strong>Baseus 30w charger + Type-C/Type-C 240w 3m + Type-C/Type-C 240w 1m + WITRN Type-C/DC 12v 5A 100w 2m + USB3.1/Type-C перехідник. Такий собі експрес набір для виживання у випадку відключень світла - потужна зарядка швиденько заряти павербанк(30000mAh за 4,5 години), два шнура під зарядку (один три метра, другий метр) шнур під роутер який точно потягне (працює від роз'єму 12v), та перехідник з юсб на тайп сі щоб той шнур включати не тільки в тайп сі</strong></li>\n<li><strong>WITRN Type-C/DC 12v 5A 100w 2m + USB3.1/Type-C перехідник - заживити роутер від павербанку, шнур 2м</strong></li>\n<li><strong>WITRN Type-C/DC 12v 5A 100w 1m + USB3.1/Type-C перехідник - заживити роутер від павербанку, шнур 1м</strong></li>\n</ol>	12u_ISe1HM1xOzniuhSYYwS45kYmrbxjP3CJX91pvano	https://donater.com.ua/images/banners/admin/l200.png	t	36	\N	2023-11-28 02:42:10	2023-11-28 19:50:34
11	zbir_na_fpv_dlya_52-go_osb	ЗБІР НА FPV ДЛЯ 52-ГО ОСБ	https://send.monobank.ua/jar/5qL3tGuQ5t	https://x.com/a_vodianko/status/1732424298143572282?s=20	<p dir="ltr">52-му Окремому Стрілецькому Батальйону потрібні нічні дрони FPV, щоб ефективно робити смерть ворогам на Авдіївському напрямку. Збираємо на п'ять дронів, ціль 90.000 грн</p>\n<p dir="ltr">Номер карти моно: 5375411209848097<br>PayPal: a.vodianko@gmail.com</p>\n<p dir="ltr">Розіграш Kalimba Thumb Piano 17 Keys Mahogany Wood Portable Finger Piano Combinations Gifts for Kids</p>\n<p dir="ltr"><strong>1 квиток - 50 грн з кодом, </strong><strong>2 квитки - 100 грн з кодом, і тд</strong></p>\n<p>&nbsp;</p>	1E0MbrI0VhpvGzluGirSYvVqzzdA1kPwH38vH_-0v-3U	https://donater.com.ua/images/banners/GoldSquealing/ok LOTERIA POST 1x1 UA vol.2 80k_ok.png	f	27	\N	2023-12-03 17:32:09	2023-12-14 02:03:21
8	52_fpv_01	Зб*р на 5 FPV для 52-го ОСБ 🐝	https://send.monobank.ua/jar/5qL3tGuQ5t	https://twitter.com/a_vodianko/status/1725862229768016335	<p>Зб*р на 5 FPV для 52-го ОСБ 🐝<br>Ситуація на Авдіївському напрямку складна. Ворог не економить FPV, запускає цілі рої др*нів по нашій піхоті. 5 FPV це мало, але зберемо більше-купимо більше!<br>Номер карти моно: 5375411209848097<br>PayPal: a.vodianko@gmail.com<br><br><strong>Розіграш Світлодіодний LED ліхтар Unibrother LY01 | З роботою до 60 годин | 15600 mAh | 278 LED | 80W за донат з кодом! Кожні 10грн донату - 1 квиток</strong></p>\n<p><strong>Приз виграла <a title="neuroprosthetist" href="../../u/neuroprosthetist">neuroprosthetist</a></strong></p>	18E4505ukqmyVpXDV1_ANqqt67iGsR6Ob9ibdPT-xOEw	/images/banners/52_fpv_01.png	f	27	\N	2023-11-26 17:08:46	2023-12-03 17:56:02
12	zbir_dlya_mizhnarodnogo_legionu_gur_mo	Збір для міжнародного легіону ГУР МО	https://send.monobank.ua/jar/XH3MNagiN	https://www.instagram.com/p/C0UAwoTNKrE/	<p>Командний збір для міжнародного легіону ГУР МО, для підсилення основного збору на: переобладнання та ремонт транспорту, спецприлади (теплаки, нічники, приціли),&nbsp;генератори, старлінки,&nbsp;базові речі для облаштування ПУ, ретранслятори.</p>\n<p>А також розіграш!</p>\n<p><strong>Той, хто задонатить від 200 грн. &mdash; має можливість виграти принт будь-якої моєї фотографії на вибір (<a href="https://instagram.com/planespotter325" target="_blank" rel="noopener">planespotter325</a>), у рамці! Кожні 200 грн. це один квиток</strong></p>\n<p><strong>Той, хто задонатить від 50 грн. &mdash; має можливість виграти книгу "Кримський інжир. Куреш"! Кожні 50 грн. це один квиток</strong></p>\n<p><strong>Той, хто задонатить будь-яку суму &mdash; має можливість виграти одну з десяти авіаційних листівок з моїми фото з різних міст України! Переможців буде обрано випадковим чином, загалом буде розіграно 10 листівок</strong></p>\n<p>🎯 Ціль банки: 25,000 грн.<br>💸 Загальна ціль: 500,000 грн.</p>\n<p>Звіт буде після завершення збору, всім дякую!</p>\n<p>Книга достається <a title="@dubysko" href="../../u/dubysko">@dubysko</a></p>	1qKTLP1LR4sXe00W7YrtrstX4HYYaNaGZdfMIZF2qgI4	https://donater.com.ua/images/banners/planespotter325/Збір-1.png	f	45	\N	2023-12-08 14:41:40	2023-12-09 17:43:35
15	mavik_3t_dlya_128_brigadi	МАВІК 3Т для 128 бригади	https://send.monobank.ua/jar/7zNep36paz?fbclid=IwAR1hewm5iH-g7SZQcyJxD-EnIieQwQBT4WggHtEP2tDwypuYnz9bM5fdMPE	https://www.facebook.com/kolya.klymentovych	<div dir="auto">Унікальний лот - репродукція картини від талановитого іконописця Сергій Колодка на тему <strong>"Запорожці пишуть листа турецькому султанові".</strong></div>\n<div dir="auto">Збір на єдиний дрон з тепловізором для 128 бригади, вони дуже просять. Мінімальна ставка 500 грн.</div>\n<div dir="auto">Велике прохання вказувати свої контакти у разі виграшу для відправки картини.</div>\n<div dir="auto">🎯 Ціль банки: 230 000 грн.<br>💸 Зібрано: 44 000,000 грн.</div>\n<div dir="auto">&nbsp;</div>\n<div dir="auto">&nbsp;</div>\n<div dir="auto">СЛАВА ЗСУ!</div>	1-7UQWTU2RxRtXP2d5Z6nBc2pUlqMTk7rt695n5JnTBs	https://donater.com.ua/images/banners/GrayAccepted/Картина_2.jpg	t	72	2023-12-28 13:30:27	2023-12-27 19:15:57	2023-12-28 13:30:27
14	10_nichnikh_fpv_dlya_52-go_osb	10 НІЧНИХ FPV ДЛЯ 52-ГО ОСБ	https://send.monobank.ua/jar/5qL3tGuQ5t	https://x.com/a_vodianko/status/1733447385865388304?s=20	<p dir="ltr">52-му Окремому Стрілецькому Батальйону потрібні нічні дрони FPV, щоб робити смерть ворогам на Авдіївському напрямку в день та в ночі! Збираємо на 10 дронів, ціль 180.000 грн</p>\n<p dir="ltr">Номер карти моно: 5375411209848097<br>PayPal: a.vodianko@gmail.com</p>\n<p dir="ltr">&nbsp;</p>\n<p dir="ltr">Розіграш призів:</p>\n<ul>\n<li>Powerbank Baseus PPAD000001 Adaman, 10000mAh, 22.5 W</li>\n<li>Книга Позивний для Йова. Хроніки вторгнення - Олександр Михед</li>\n<li>&nbsp;</li>\n</ul>\n<p dir="ltr"><strong>1 квиток - 50 грн з кодом, </strong></p>\n<p dir="ltr"><strong>2 квитки - 100 грн з кодом, і тд</strong></p>\n<p>&nbsp;</p>	16_HiEMFQY1l0O22swu-wkX01RWeVivM8LNnQ_69yeAw	https://donater.com.ua/images/banners/GoldSquealing/ok LOTERIA №2 POST 1x1 UA vol.2 180k.png	t	27	\N	2023-12-15 03:05:13	2023-12-15 17:19:22
13	taras	taras	https://send.monobank.ua/jar/3jDJbi56Rg	https://www.facebook.com/taras.yarosh.5/posts/pfbid0C8GoDeyf5jVengVypmJN5VvhaMoMnX4gXvbNuJTSnCJmYCD3Z1BUPJMHC75xzmhDl	<div dir="auto">Наступний лот - тубус "<strong>Різдв'ний</strong>". Збір на дрони для 15 бригади НГУ "Кара-Даг". Наша допомога для захисників триває, будь ласка долучайтесь</div>\n<div dir="auto">Ціль у нас одна Перемога України</div>\n<div dir="auto">Зусилля усі для допомоги захисникам!</div>\n<div dir="auto">PayPal</div>\n<div dir="auto">t.yarosch@gmail.com</div>\n<div dir="auto">Приватбанк:</div>\n<div dir="auto">5168745111258668</div>\n<div dir="auto"><a class="x1i10hfl xjbqb8w x6umtig x1b1mbwd xaqea5y xav7gou x9f619 x1ypdohk xt0psk2 xe8uvvx xdj266r x11i5rnm xat24cr x1mh8g0r xexx8yu x4uap5 x18d9i69 xkhd6sd x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xt0b8zv x1fey0fg" tabindex="0" role="link" href="https://goo-gl.me/ZcaYN?fbclid=IwAR0NBzH52LlcieVr4g3LwSZ6b017yWYc6a39zBUkO_s7E9SCyzuwb8tUmZk" target="_blank" rel="nofollow noopener noreferrer">https://goo-gl.me/ZcaYN</a></div>\n<div dir="auto">monobank:</div>\n<div dir="auto">5375411205154011</div>\n<div dir="auto"><a class="x1i10hfl xjbqb8w x6umtig x1b1mbwd xaqea5y xav7gou x9f619 x1ypdohk xt0psk2 xe8uvvx xdj266r x11i5rnm xat24cr x1mh8g0r xexx8yu x4uap5 x18d9i69 xkhd6sd x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xt0b8zv x1fey0fg" tabindex="0" role="link" href="https://send.monobank.ua/jar/3jDJbi56Rg?fbclid=IwAR0zAHqVg2Ji6XSFjcz36XOBD4F8F-5Qp2sA2W4DtsF-H7YSaQolg5i3tzA" target="_blank" rel="nofollow noopener noreferrer">https://send.monobank.ua/jar/3jDJbi56Rg</a></div>\n<div dir="auto">Криптовалюта</div>\n<div dir="auto"><a class="x1i10hfl xjbqb8w x6umtig x1b1mbwd xaqea5y xav7gou x9f619 x1ypdohk xt0psk2 xe8uvvx xdj266r x11i5rnm xat24cr x1mh8g0r xexx8yu x4uap5 x18d9i69 xkhd6sd x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xt0b8zv x1fey0fg" tabindex="0" role="link" href="https://l.facebook.com/l.php?u=https%3A%2F%2Ftaras-yarosh.pay.whitepay.com%2F%3Ffbclid%3DIwAR1Z75bazWcglOXUyBVescZ26Y4OZH-3UuofbEN1Nv7fqB5wcnMKlTM2mXU&amp;h=AT0jIvnPaELixhDRMEdhs7RTrdF8SQdNAJkWUy50MKi7RIxaebWjWsbvoIkD98NI-JoJavHNh9XQSQPu0N7vQ6M2E9HdtxCIwss1MZi3UZ-WLC10VWcA6B-Wbz5N-Av38UWZ&amp;__tn__=-UK-R&amp;c[0]=AT3GGc6rOQCpXLgcSIXxii1_LRGPzha5o97Hm0QtBKxiO15LZEItG6bvhldbNIILGAvF7kKKjAsLbibbizf7TJincBTIOaxEL_AYMGyorz6XFM5_wDQ-KaLWz9xwY65yF3CSWUloJRzWogI2pvXME-YE1w" target="_blank" rel="nofollow noopener noreferrer">https://taras-yarosh.pay.whitepay.com</a></div>\n<div dir="auto">&nbsp;</div>\n<div dir="auto"><strong>За кожні 50 грн донату з кодом з сайту шанс отримати книжку Анна Гін - Як ти там?</strong></div>	1EMzvmIksz1K0EXo0e1H38lrsstHs8DnCw-IDhAMfu-M	https://donater.com.ua/images/banners/taras_yarosh/Різдво.jpg	t	54	\N	2023-12-09 21:10:27	2023-12-26 20:07:58
\.


--
-- Name: volunteers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.volunteers_id_seq', 15, true);


--
-- Name: cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: donates_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.donates
    ADD CONSTRAINT donates_pkey PRIMARY KEY (id);


--
-- Name: donates_uniq_hash_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.donates
    ADD CONSTRAINT donates_uniq_hash_unique UNIQUE (uniq_hash);


--
-- Name: failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: oauth_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_access_tokens
    ADD CONSTRAINT oauth_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: oauth_auth_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_auth_codes
    ADD CONSTRAINT oauth_auth_codes_pkey PRIMARY KEY (id);


--
-- Name: oauth_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_clients
    ADD CONSTRAINT oauth_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_personal_access_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_personal_access_clients
    ADD CONSTRAINT oauth_personal_access_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_refresh_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.oauth_refresh_tokens
    ADD CONSTRAINT oauth_refresh_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: user_links_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_links
    ADD CONSTRAINT user_links_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_telegram_id_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_telegram_id_unique UNIQUE (telegram_id);


--
-- Name: volunteers_key_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.volunteers
    ADD CONSTRAINT volunteers_key_unique UNIQUE (key);


--
-- Name: volunteers_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.volunteers
    ADD CONSTRAINT volunteers_pkey PRIMARY KEY (id);


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
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: docker
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM docker;
GRANT ALL ON SCHEMA public TO docker;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

