--
-- PostgreSQL database dump
--

\restrict urSzVfPkSgTkrEWd4icOjPBO1HKh3ZXBgSZOqIleEtpxRpVAGM2d1FYxRjeVHZa

-- Dumped from database version 18.1 (Homebrew)
-- Dumped by pg_dump version 18.1 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
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
-- Name: cache; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO elkadey;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO elkadey;

--
-- Name: cart_item_pricing_inputs; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.cart_item_pricing_inputs (
    id bigint NOT NULL,
    pricing_template_input_id bigint NOT NULL,
    cart_item_id bigint NOT NULL,
    value_number numeric(15,4),
    value_text text,
    value_boolean boolean,
    value_date date,
    value_datetime timestamp(0) without time zone,
    value_json jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.cart_item_pricing_inputs OWNER TO elkadey;

--
-- Name: cart_item_pricing_inputs_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.cart_item_pricing_inputs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cart_item_pricing_inputs_id_seq OWNER TO elkadey;

--
-- Name: cart_item_pricing_inputs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.cart_item_pricing_inputs_id_seq OWNED BY public.cart_item_pricing_inputs.id;


--
-- Name: cart_items; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.cart_items (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    cart_id bigint NOT NULL,
    product_id bigint NOT NULL,
    price numeric(12,4) NOT NULL,
    pricing_version integer NOT NULL,
    pricing_snapshot jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    product_variant_id bigint
);


ALTER TABLE public.cart_items OWNER TO elkadey;

--
-- Name: cart_items_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.cart_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cart_items_id_seq OWNER TO elkadey;

--
-- Name: cart_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.cart_items_id_seq OWNED BY public.cart_items.id;


--
-- Name: carts; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.carts (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    customer_id bigint NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.carts OWNER TO elkadey;

--
-- Name: carts_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.carts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carts_id_seq OWNER TO elkadey;

--
-- Name: carts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.carts_id_seq OWNED BY public.carts.id;


--
-- Name: companys; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.companys (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    plan_type character varying(255) DEFAULT 'free'::character varying NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    settings jsonb,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT companys_plan_type_check CHECK (((plan_type)::text = ANY ((ARRAY['free'::character varying, 'premium'::character varying])::text[]))),
    CONSTRAINT companys_status_check CHECK (((status)::text = ANY ((ARRAY['active'::character varying, 'inactive'::character varying])::text[])))
);


ALTER TABLE public.companys OWNER TO elkadey;

--
-- Name: companys_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.companys_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.companys_id_seq OWNER TO elkadey;

--
-- Name: companys_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.companys_id_seq OWNED BY public.companys.id;


--
-- Name: custmers; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.custmers (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255),
    phone character varying(255),
    address character varying(255),
    metadata jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.custmers OWNER TO elkadey;

--
-- Name: custmers_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.custmers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.custmers_id_seq OWNER TO elkadey;

--
-- Name: custmers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.custmers_id_seq OWNED BY public.custmers.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO elkadey;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO elkadey;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: fields; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.fields (
    id bigint NOT NULL,
    template_id bigint NOT NULL,
    key character varying(50) NOT NULL,
    label character varying(255) NOT NULL,
    field_type character varying(255) NOT NULL,
    unit character varying(50),
    required boolean DEFAULT true NOT NULL,
    default_value character varying(255),
    options jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT fields_field_type_check CHECK (((field_type)::text = ANY ((ARRAY['string'::character varying, 'number'::character varying, 'boolean'::character varying, 'select'::character varying, 'date'::character varying, 'datetime'::character varying])::text[])))
);


ALTER TABLE public.fields OWNER TO elkadey;

--
-- Name: fields_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.fields_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fields_id_seq OWNER TO elkadey;

--
-- Name: fields_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.fields_id_seq OWNED BY public.fields.id;


--
-- Name: invoices; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.invoices (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.invoices OWNER TO elkadey;

--
-- Name: invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.invoices_id_seq OWNER TO elkadey;

--
-- Name: invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.invoices_id_seq OWNED BY public.invoices.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO elkadey;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: elkadey
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


ALTER TABLE public.jobs OWNER TO elkadey;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO elkadey;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO elkadey;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO elkadey;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: order_items; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.order_items (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    order_id bigint NOT NULL,
    product_id bigint NOT NULL,
    quantity integer DEFAULT 1 NOT NULL,
    price numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    pricing_version integer NOT NULL,
    pricing_snapshot jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.order_items OWNER TO elkadey;

--
-- Name: order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.order_items_id_seq OWNER TO elkadey;

--
-- Name: order_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.order_items_id_seq OWNED BY public.order_items.id;


--
-- Name: orders; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.orders (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    customer_id bigint NOT NULL,
    total_price numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    metadata jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    product_variant_id bigint
);


ALTER TABLE public.orders OWNER TO elkadey;

--
-- Name: orders_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.orders_id_seq OWNER TO elkadey;

--
-- Name: orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.orders_id_seq OWNED BY public.orders.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO elkadey;

--
-- Name: pricing_template_inputs; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.pricing_template_inputs (
    id bigint NOT NULL,
    template_id bigint NOT NULL,
    key character varying(50) NOT NULL,
    label character varying(255) NOT NULL,
    input_type character varying(255) DEFAULT 'number'::character varying NOT NULL,
    unit character varying(50) NOT NULL,
    options jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT pricing_template_inputs_input_type_check CHECK (((input_type)::text = ANY ((ARRAY['number'::character varying, 'string'::character varying, 'boolean'::character varying, 'date'::character varying, 'select'::character varying])::text[])))
);


ALTER TABLE public.pricing_template_inputs OWNER TO elkadey;

--
-- Name: pricing_template_inputs_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.pricing_template_inputs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pricing_template_inputs_id_seq OWNER TO elkadey;

--
-- Name: pricing_template_inputs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.pricing_template_inputs_id_seq OWNED BY public.pricing_template_inputs.id;


--
-- Name: product_field_values; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.product_field_values (
    id bigint NOT NULL,
    field_id bigint NOT NULL,
    product_id bigint NOT NULL,
    value_number numeric(15,4),
    value_text text,
    value_boolean boolean,
    value_date date,
    value_datetime timestamp(0) without time zone,
    value_json jsonb,
    extra jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.product_field_values OWNER TO elkadey;

--
-- Name: product_field_values_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.product_field_values_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.product_field_values_id_seq OWNER TO elkadey;

--
-- Name: product_field_values_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.product_field_values_id_seq OWNED BY public.product_field_values.id;


--
-- Name: product_variants; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.product_variants (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    sku character varying(255) NOT NULL,
    price_override numeric(12,4),
    stock_quantity integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.product_variants OWNER TO elkadey;

--
-- Name: product_variants_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.product_variants_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.product_variants_id_seq OWNER TO elkadey;

--
-- Name: product_variants_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.product_variants_id_seq OWNED BY public.product_variants.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.products (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    template_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    sku character varying(255) NOT NULL,
    description character varying(255),
    base_rate numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    extra jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.products OWNER TO elkadey;

--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.products_id_seq OWNER TO elkadey;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- Name: purchase_order_items; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.purchase_order_items (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.purchase_order_items OWNER TO elkadey;

--
-- Name: purchase_order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.purchase_order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.purchase_order_items_id_seq OWNER TO elkadey;

--
-- Name: purchase_order_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.purchase_order_items_id_seq OWNED BY public.purchase_order_items.id;


--
-- Name: purchase_orders; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.purchase_orders (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.purchase_orders OWNER TO elkadey;

--
-- Name: purchase_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.purchase_orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.purchase_orders_id_seq OWNER TO elkadey;

--
-- Name: purchase_orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.purchase_orders_id_seq OWNED BY public.purchase_orders.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO elkadey;

--
-- Name: stock_movements; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.stock_movements (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.stock_movements OWNER TO elkadey;

--
-- Name: stock_movements_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.stock_movements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.stock_movements_id_seq OWNER TO elkadey;

--
-- Name: stock_movements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.stock_movements_id_seq OWNED BY public.stock_movements.id;


--
-- Name: stocks; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.stocks (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.stocks OWNER TO elkadey;

--
-- Name: stocks_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.stocks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.stocks_id_seq OWNER TO elkadey;

--
-- Name: stocks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.stocks_id_seq OWNED BY public.stocks.id;


--
-- Name: suppliers; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.suppliers (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.suppliers OWNER TO elkadey;

--
-- Name: suppliers_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.suppliers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.suppliers_id_seq OWNER TO elkadey;

--
-- Name: suppliers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.suppliers_id_seq OWNED BY public.suppliers.id;


--
-- Name: templates; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.templates (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    name character varying(100) NOT NULL,
    description text,
    expression text,
    pricing_version integer DEFAULT 1 NOT NULL,
    parent_template_id bigint,
    is_active boolean DEFAULT true NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.templates OWNER TO elkadey;

--
-- Name: templates_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.templates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.templates_id_seq OWNER TO elkadey;

--
-- Name: templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.templates_id_seq OWNED BY public.templates.id;


--
-- Name: transactions; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.transactions (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.transactions OWNER TO elkadey;

--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transactions_id_seq OWNER TO elkadey;

--
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.transactions_id_seq OWNED BY public.transactions.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    role character varying(255) NOT NULL,
    permissions jsonb,
    metadata jsonb,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    company_id bigint,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'user'::character varying, 'guest'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO elkadey;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO elkadey;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: variant_attribute_values; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.variant_attribute_values (
    id bigint NOT NULL,
    product_variant_id bigint NOT NULL,
    attribute_key character varying(50) NOT NULL,
    attribute_value character varying(50) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.variant_attribute_values OWNER TO elkadey;

--
-- Name: variant_attribute_values_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.variant_attribute_values_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.variant_attribute_values_id_seq OWNER TO elkadey;

--
-- Name: variant_attribute_values_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.variant_attribute_values_id_seq OWNED BY public.variant_attribute_values.id;


--
-- Name: warehouses; Type: TABLE; Schema: public; Owner: elkadey
--

CREATE TABLE public.warehouses (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    address text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.warehouses OWNER TO elkadey;

--
-- Name: warehouses_id_seq; Type: SEQUENCE; Schema: public; Owner: elkadey
--

CREATE SEQUENCE public.warehouses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.warehouses_id_seq OWNER TO elkadey;

--
-- Name: warehouses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: elkadey
--

ALTER SEQUENCE public.warehouses_id_seq OWNED BY public.warehouses.id;


--
-- Name: cart_item_pricing_inputs id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_item_pricing_inputs ALTER COLUMN id SET DEFAULT nextval('public.cart_item_pricing_inputs_id_seq'::regclass);


--
-- Name: cart_items id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_items ALTER COLUMN id SET DEFAULT nextval('public.cart_items_id_seq'::regclass);


--
-- Name: carts id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.carts ALTER COLUMN id SET DEFAULT nextval('public.carts_id_seq'::regclass);


--
-- Name: companys id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.companys ALTER COLUMN id SET DEFAULT nextval('public.companys_id_seq'::regclass);


--
-- Name: custmers id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.custmers ALTER COLUMN id SET DEFAULT nextval('public.custmers_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: fields id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.fields ALTER COLUMN id SET DEFAULT nextval('public.fields_id_seq'::regclass);


--
-- Name: invoices id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.invoices ALTER COLUMN id SET DEFAULT nextval('public.invoices_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: order_items id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.order_items ALTER COLUMN id SET DEFAULT nextval('public.order_items_id_seq'::regclass);


--
-- Name: orders id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.orders ALTER COLUMN id SET DEFAULT nextval('public.orders_id_seq'::regclass);


--
-- Name: pricing_template_inputs id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.pricing_template_inputs ALTER COLUMN id SET DEFAULT nextval('public.pricing_template_inputs_id_seq'::regclass);


--
-- Name: product_field_values id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.product_field_values ALTER COLUMN id SET DEFAULT nextval('public.product_field_values_id_seq'::regclass);


--
-- Name: product_variants id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.product_variants ALTER COLUMN id SET DEFAULT nextval('public.product_variants_id_seq'::regclass);


--
-- Name: products id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- Name: purchase_order_items id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.purchase_order_items ALTER COLUMN id SET DEFAULT nextval('public.purchase_order_items_id_seq'::regclass);


--
-- Name: purchase_orders id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.purchase_orders ALTER COLUMN id SET DEFAULT nextval('public.purchase_orders_id_seq'::regclass);


--
-- Name: stock_movements id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.stock_movements ALTER COLUMN id SET DEFAULT nextval('public.stock_movements_id_seq'::regclass);


--
-- Name: stocks id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.stocks ALTER COLUMN id SET DEFAULT nextval('public.stocks_id_seq'::regclass);


--
-- Name: suppliers id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.suppliers ALTER COLUMN id SET DEFAULT nextval('public.suppliers_id_seq'::regclass);


--
-- Name: templates id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.templates ALTER COLUMN id SET DEFAULT nextval('public.templates_id_seq'::regclass);


--
-- Name: transactions id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.transactions ALTER COLUMN id SET DEFAULT nextval('public.transactions_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: variant_attribute_values id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.variant_attribute_values ALTER COLUMN id SET DEFAULT nextval('public.variant_attribute_values_id_seq'::regclass);


--
-- Name: warehouses id; Type: DEFAULT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.warehouses ALTER COLUMN id SET DEFAULT nextval('public.warehouses_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: cart_item_pricing_inputs; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.cart_item_pricing_inputs (id, pricing_template_input_id, cart_item_id, value_number, value_text, value_boolean, value_date, value_datetime, value_json, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: cart_items; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.cart_items (id, company_id, cart_id, product_id, price, pricing_version, pricing_snapshot, created_at, updated_at, product_variant_id) FROM stdin;
\.


--
-- Data for Name: carts; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.carts (id, company_id, customer_id, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: companys; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.companys (id, name, slug, plan_type, status, settings, deleted_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: custmers; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.custmers (id, company_id, name, email, phone, address, metadata, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: fields; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.fields (id, template_id, key, label, field_type, unit, required, default_value, options, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: invoices; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.invoices (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.migrations (id, migration, batch) FROM stdin;
4	0001_01_01_000000_create_users_table	1
5	0001_01_01_000001_create_cache_table	1
6	0001_01_01_000002_create_jobs_table	1
7	2026_02_05_172224_create_companys_table	1
8	2026_02_05_172510_create_custmers_table	1
9	2026_02_05_173027_create_templates_table	1
10	2026_02_05_173056_create_fields_table	1
11	2026_02_05_173117_create_products_table	1
12	2026_02_05_173131_create_product_field_values_table	1
13	2026_02_05_173205_create_carts_table	1
14	2026_02_05_173219_create_cart_items_table	2
15	2026_02_05_173305_create_orders_table	2
16	2026_02_05_173433_create_warehouses_table	2
17	2026_02_05_173440_create_stocks_table	2
18	2026_02_05_173457_create_suppliers_table	2
19	2026_02_05_173526_create_purchase_order_items_table	2
20	2026_02_05_173600_create_purchase_orders_table	2
21	2026_02_05_173621_create_stock_movements_table	2
22	2026_02_05_173713_create_transactions_table	2
23	2026_02_05_173727_create_invoices_table	2
24	2026_02_06_181937_create_pricing_template_inputs_table	2
25	2026_02_06_183143_create_cart_item_pricing_inputs_table	2
26	2026_02_07_135230_create_order_items_table	3
27	2026_02_09_180831_create_product_variants_table	3
28	2026_02_09_180909_create_variant_attribute_values_table	3
29	2026_02_09_181423_add_company_id_to_users_table	3
30	2026_02_09_182447_add_product_variant_id_to_cart_items_table	3
31	2026_02_09_182727_add_product_variant_id_to_orders_table	3
\.


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.order_items (id, company_id, order_id, product_id, quantity, price, pricing_version, pricing_snapshot, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.orders (id, company_id, customer_id, total_price, status, metadata, created_at, updated_at, product_variant_id) FROM stdin;
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: pricing_template_inputs; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.pricing_template_inputs (id, template_id, key, label, input_type, unit, options, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: product_field_values; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.product_field_values (id, field_id, product_id, value_number, value_text, value_boolean, value_date, value_datetime, value_json, extra, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: product_variants; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.product_variants (id, product_id, sku, price_override, stock_quantity, is_active, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.products (id, company_id, template_id, name, sku, description, base_rate, is_active, extra, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: purchase_order_items; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.purchase_order_items (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: purchase_orders; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.purchase_orders (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: stock_movements; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.stock_movements (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: stocks; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.stocks (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: suppliers; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.suppliers (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: templates; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.templates (id, company_id, name, description, expression, pricing_version, parent_template_id, is_active, deleted_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: transactions; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.transactions (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.users (id, name, email, email_verified_at, password, role, permissions, metadata, remember_token, created_at, updated_at, company_id) FROM stdin;
\.


--
-- Data for Name: variant_attribute_values; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.variant_attribute_values (id, product_variant_id, attribute_key, attribute_value, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: warehouses; Type: TABLE DATA; Schema: public; Owner: elkadey
--

COPY public.warehouses (id, company_id, name, address, created_at, updated_at) FROM stdin;
\.


--
-- Name: cart_item_pricing_inputs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.cart_item_pricing_inputs_id_seq', 1, false);


--
-- Name: cart_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.cart_items_id_seq', 1, false);


--
-- Name: carts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.carts_id_seq', 1, false);


--
-- Name: companys_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.companys_id_seq', 1, false);


--
-- Name: custmers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.custmers_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: fields_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.fields_id_seq', 1, false);


--
-- Name: invoices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.invoices_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.migrations_id_seq', 31, true);


--
-- Name: order_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.order_items_id_seq', 1, false);


--
-- Name: orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.orders_id_seq', 1, false);


--
-- Name: pricing_template_inputs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.pricing_template_inputs_id_seq', 1, false);


--
-- Name: product_field_values_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.product_field_values_id_seq', 1, false);


--
-- Name: product_variants_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.product_variants_id_seq', 1, false);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.products_id_seq', 1, false);


--
-- Name: purchase_order_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.purchase_order_items_id_seq', 1, false);


--
-- Name: purchase_orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.purchase_orders_id_seq', 1, false);


--
-- Name: stock_movements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.stock_movements_id_seq', 1, false);


--
-- Name: stocks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.stocks_id_seq', 1, false);


--
-- Name: suppliers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.suppliers_id_seq', 1, false);


--
-- Name: templates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.templates_id_seq', 1, false);


--
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.transactions_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: variant_attribute_values_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.variant_attribute_values_id_seq', 1, false);


--
-- Name: warehouses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: elkadey
--

SELECT pg_catalog.setval('public.warehouses_id_seq', 1, false);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: cart_item_pricing_inputs cart_item_pricing_inputs_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_item_pricing_inputs
    ADD CONSTRAINT cart_item_pricing_inputs_pkey PRIMARY KEY (id);


--
-- Name: cart_items cart_items_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_pkey PRIMARY KEY (id);


--
-- Name: carts carts_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.carts
    ADD CONSTRAINT carts_pkey PRIMARY KEY (id);


--
-- Name: companys companys_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.companys
    ADD CONSTRAINT companys_pkey PRIMARY KEY (id);


--
-- Name: companys companys_slug_unique; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.companys
    ADD CONSTRAINT companys_slug_unique UNIQUE (slug);


--
-- Name: custmers custmers_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.custmers
    ADD CONSTRAINT custmers_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: fields fields_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.fields
    ADD CONSTRAINT fields_pkey PRIMARY KEY (id);


--
-- Name: fields fields_template_id_key_unique; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.fields
    ADD CONSTRAINT fields_template_id_key_unique UNIQUE (template_id, key);


--
-- Name: invoices invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: order_items order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_pkey PRIMARY KEY (id);


--
-- Name: orders orders_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: pricing_template_inputs pricing_template_inputs_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.pricing_template_inputs
    ADD CONSTRAINT pricing_template_inputs_pkey PRIMARY KEY (id);


--
-- Name: pricing_template_inputs pricing_template_inputs_template_id_key_unique; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.pricing_template_inputs
    ADD CONSTRAINT pricing_template_inputs_template_id_key_unique UNIQUE (template_id, key);


--
-- Name: product_field_values product_field_values_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.product_field_values
    ADD CONSTRAINT product_field_values_pkey PRIMARY KEY (id);


--
-- Name: product_variants product_variants_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.product_variants
    ADD CONSTRAINT product_variants_pkey PRIMARY KEY (id);


--
-- Name: product_variants product_variants_sku_unique; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.product_variants
    ADD CONSTRAINT product_variants_sku_unique UNIQUE (sku);


--
-- Name: products products_company_id_sku_unique; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_company_id_sku_unique UNIQUE (company_id, sku);


--
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: purchase_order_items purchase_order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.purchase_order_items
    ADD CONSTRAINT purchase_order_items_pkey PRIMARY KEY (id);


--
-- Name: purchase_orders purchase_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: stock_movements stock_movements_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.stock_movements
    ADD CONSTRAINT stock_movements_pkey PRIMARY KEY (id);


--
-- Name: stocks stocks_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.stocks
    ADD CONSTRAINT stocks_pkey PRIMARY KEY (id);


--
-- Name: suppliers suppliers_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.suppliers
    ADD CONSTRAINT suppliers_pkey PRIMARY KEY (id);


--
-- Name: templates templates_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.templates
    ADD CONSTRAINT templates_pkey PRIMARY KEY (id);


--
-- Name: transactions transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: variant_attribute_values variant_attribute_values_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.variant_attribute_values
    ADD CONSTRAINT variant_attribute_values_pkey PRIMARY KEY (id);


--
-- Name: variant_attribute_values variant_attribute_values_product_variant_id_attribute_key_uniqu; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.variant_attribute_values
    ADD CONSTRAINT variant_attribute_values_product_variant_id_attribute_key_uniqu UNIQUE (product_variant_id, attribute_key);


--
-- Name: warehouses warehouses_pkey; Type: CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.warehouses
    ADD CONSTRAINT warehouses_pkey PRIMARY KEY (id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: cart_item_pricing_inputs_pricing_template_input_id_cart_item_id; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX cart_item_pricing_inputs_pricing_template_input_id_cart_item_id ON public.cart_item_pricing_inputs USING btree (pricing_template_input_id, cart_item_id);


--
-- Name: cart_items_company_id_cart_id_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX cart_items_company_id_cart_id_index ON public.cart_items USING btree (company_id, cart_id);


--
-- Name: carts_company_id_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX carts_company_id_index ON public.carts USING btree (company_id);


--
-- Name: carts_customer_id_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX carts_customer_id_index ON public.carts USING btree (customer_id);


--
-- Name: fields_template_id_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX fields_template_id_index ON public.fields USING btree (template_id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: orders_company_id_customer_id_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX orders_company_id_customer_id_index ON public.orders USING btree (company_id, customer_id);


--
-- Name: product_field_values_field_id_product_id_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX product_field_values_field_id_product_id_index ON public.product_field_values USING btree (field_id, product_id);


--
-- Name: products_company_id_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX products_company_id_index ON public.products USING btree (company_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: templates_company_id_index; Type: INDEX; Schema: public; Owner: elkadey
--

CREATE INDEX templates_company_id_index ON public.templates USING btree (company_id);


--
-- Name: cart_item_pricing_inputs cart_item_pricing_inputs_cart_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_item_pricing_inputs
    ADD CONSTRAINT cart_item_pricing_inputs_cart_item_id_foreign FOREIGN KEY (cart_item_id) REFERENCES public.cart_items(id) ON DELETE CASCADE;


--
-- Name: cart_item_pricing_inputs cart_item_pricing_inputs_pricing_template_input_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_item_pricing_inputs
    ADD CONSTRAINT cart_item_pricing_inputs_pricing_template_input_id_foreign FOREIGN KEY (pricing_template_input_id) REFERENCES public.pricing_template_inputs(id) ON DELETE CASCADE;


--
-- Name: cart_items cart_items_cart_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_cart_id_foreign FOREIGN KEY (cart_id) REFERENCES public.carts(id) ON DELETE CASCADE;


--
-- Name: cart_items cart_items_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companys(id) ON DELETE CASCADE;


--
-- Name: cart_items cart_items_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: cart_items cart_items_product_variant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.cart_items
    ADD CONSTRAINT cart_items_product_variant_id_foreign FOREIGN KEY (product_variant_id) REFERENCES public.product_variants(id);


--
-- Name: carts carts_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.carts
    ADD CONSTRAINT carts_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companys(id) ON DELETE CASCADE;


--
-- Name: carts carts_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.carts
    ADD CONSTRAINT carts_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.custmers(id) ON DELETE CASCADE;


--
-- Name: custmers custmers_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.custmers
    ADD CONSTRAINT custmers_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companys(id) ON DELETE CASCADE;


--
-- Name: fields fields_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.fields
    ADD CONSTRAINT fields_template_id_foreign FOREIGN KEY (template_id) REFERENCES public.templates(id) ON DELETE CASCADE;


--
-- Name: order_items order_items_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companys(id) ON DELETE CASCADE;


--
-- Name: order_items order_items_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_order_id_foreign FOREIGN KEY (order_id) REFERENCES public.orders(id) ON DELETE CASCADE;


--
-- Name: order_items order_items_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: orders orders_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companys(id) ON DELETE CASCADE;


--
-- Name: orders orders_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.custmers(id) ON DELETE CASCADE;


--
-- Name: orders orders_product_variant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_product_variant_id_foreign FOREIGN KEY (product_variant_id) REFERENCES public.product_variants(id);


--
-- Name: pricing_template_inputs pricing_template_inputs_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.pricing_template_inputs
    ADD CONSTRAINT pricing_template_inputs_template_id_foreign FOREIGN KEY (template_id) REFERENCES public.templates(id) ON DELETE CASCADE;


--
-- Name: product_field_values product_field_values_field_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.product_field_values
    ADD CONSTRAINT product_field_values_field_id_foreign FOREIGN KEY (field_id) REFERENCES public.fields(id) ON DELETE CASCADE;


--
-- Name: product_field_values product_field_values_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.product_field_values
    ADD CONSTRAINT product_field_values_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: product_variants product_variants_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.product_variants
    ADD CONSTRAINT product_variants_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: products products_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companys(id) ON DELETE CASCADE;


--
-- Name: products products_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_template_id_foreign FOREIGN KEY (template_id) REFERENCES public.templates(id) ON DELETE CASCADE;


--
-- Name: templates templates_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.templates
    ADD CONSTRAINT templates_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companys(id) ON DELETE CASCADE;


--
-- Name: templates templates_parent_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.templates
    ADD CONSTRAINT templates_parent_template_id_foreign FOREIGN KEY (parent_template_id) REFERENCES public.templates(id) ON DELETE SET NULL;


--
-- Name: users users_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companys(id) ON DELETE SET NULL;


--
-- Name: variant_attribute_values variant_attribute_values_product_variant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.variant_attribute_values
    ADD CONSTRAINT variant_attribute_values_product_variant_id_foreign FOREIGN KEY (product_variant_id) REFERENCES public.product_variants(id) ON DELETE CASCADE;


--
-- Name: warehouses warehouses_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: elkadey
--

ALTER TABLE ONLY public.warehouses
    ADD CONSTRAINT warehouses_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companys(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict urSzVfPkSgTkrEWd4icOjPBO1HKh3ZXBgSZOqIleEtpxRpVAGM2d1FYxRjeVHZa

