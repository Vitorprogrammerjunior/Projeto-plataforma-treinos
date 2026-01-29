# 📋 CONTEXTO DO PROJETO - ADRI TREINOS

> **Este arquivo serve para contextualizar uma IA (como GitHub Copilot) sobre a estrutura completa do projeto, facilitando modificações futuras.**

---

## 🎯 OBJETIVO DO PROJETO

Plataforma web para venda de vídeos de treino de uma personal trainer chamada "Adri". O modelo de negócio é:
1. Usuário se cadastra gratuitamente
2. Visualiza vídeos organizados por abas (categorias definidas pelo admin)
3. Compra um plano (pagamento único ou mensal)
4. Ganha acesso a todos os vídeos premium durante a vigência do plano

### Modelo Simplificado (v2)
- **"Pagou, liberou"** - Sistema simples de acesso
- **Abas** - Vídeos organizados em abas/categorias criadas pelo admin
- **Planos flexíveis** - Único (one-time) ou mensal (recorrente)
- **Mercado Pago** - Gateway de pagamento com suporte a PIX, cartão e boleto

---

## 🛠️ STACK TECNOLÓGICA

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| PHP | 8.2+ | Backend |
| Laravel | 12.x | Framework principal |
| SQLite | 3.x | Banco de dados |
| Tailwind CSS | 3.x | Estilização |
| Vite | 7.x | Build de assets |
| Laravel Breeze | - | Autenticação |
| Mercado Pago | API v1 | Pagamentos (sandbox para testes) |

---

## 🎨 DESIGN SYSTEM

### Paleta de Cores
- **Primária:** Vermelho (`red-600` = #dc2626)
- **Background:** Preto/Cinza escuro (`gray-900` = #111827)
- **Cards:** Cinza escuro (`gray-800` = #1f2937)
- **Texto principal:** Branco (`white`)
- **Texto secundário:** Cinza (`gray-400`)
- **Bordas:** Cinza (`gray-700`)
- **Inputs:** Cinza escuro (`gray-700`) com foco vermelho

### Abordagem
- **Mobile-first** - Todas as views são responsivas
- **Dark theme** - Tema escuro em toda a aplicação
- **Fonte:** Figtree (Google Fonts)

---

## 📁 ESTRUTURA DE ARQUIVOS

```
ADRI-TREINOS/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                          # Controllers do painel administrativo
│   │   │   │   ├── AdminDashboardController.php   # Dashboard admin com métricas
│   │   │   │   ├── PlanController.php             # CRUD de planos
│   │   │   │   ├── TabController.php              # CRUD de abas (categorias)
│   │   │   │   ├── UserController.php             # Gerenciamento de usuários
│   │   │   │   └── VideoController.php            # CRUD de vídeos
│   │   │   ├── DashboardController.php         # Dashboard do usuário logado
│   │   │   ├── HomeController.php              # Landing page pública
│   │   │   ├── PaymentController.php           # Callbacks de pagamento
│   │   │   └── PlanController.php              # Listagem de planos para compra
│   │   ├── Middleware/
│   │   │   ├── EnsureUserHasActiveAccess.php   # Verifica se tem assinatura ativa
│   │   │   └── EnsureUserIsAdmin.php           # Verifica se é administrador
│   │   └── Requests/
│   │       ├── PlanRequest.php                 # Validação de planos
│   │       └── VideoRequest.php                # Validação de vídeos (inclui tab_id)
│   ├── Models/
│   │   ├── Plan.php                            # Modelo de planos (com type: single/monthly)
│   │   ├── Subscription.php                    # Modelo de assinaturas
│   │   ├── Tab.php                             # Modelo de abas (categorias de vídeos)
│   │   ├── User.php                            # Modelo de usuários (modificado)
│   │   └── Video.php                           # Modelo de vídeos (com tab_id)
│   └── Services/
│       ├── PaymentService.php                  # Integração com Mercado Pago
│       └── SubscriptionService.php             # Lógica de negócio de assinaturas
├── database/
│   ├── migrations/
│   │   ├── 2026_01_21_000001_create_plans_table.php
│   │   ├── 2026_01_21_000002_create_subscriptions_table.php
│   │   ├── 2026_01_21_000003_create_videos_table.php
│   │   ├── 2026_01_21_000004_add_admin_to_users_table.php
│   │   ├── 2026_01_29_000001_create_tabs_table.php      # Tabela de abas
│   │   ├── 2026_01_29_000002_add_tab_id_to_videos_table.php
│   │   └── 2026_01_29_000003_add_type_to_plans_table.php
│   └── seeders/
│       ├── AdminSeeder.php                     # Cria usuário admin
│       ├── PlanSeeder.php                      # Cria planos padrão
│       ├── TabSeeder.php                       # Cria abas de exemplo
│       ├── VideoSeeder.php                     # Cria vídeos de exemplo
│       └── DatabaseSeeder.php                  # Orquestra os seeders
├── resources/views/
│   ├── admin/                                  # Views do painel admin
│   │   ├── dashboard.blade.php
│   │   ├── tabs/                               # CRUD de abas
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── videos/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── plans/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   └── users/
│   │       ├── index.blade.php
│   │       └── show.blade.php
│   ├── auth/                                   # Views de autenticação (tematizadas)
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── components/
│   │   └── admin-layout.blade.php              # Layout do painel admin
│   ├── dashboard/
│   │   └── subscriptions.blade.php             # Histórico de assinaturas
│   ├── layouts/
│   │   ├── app.blade.php                       # Layout principal (dark theme)
│   │   ├── guest.blade.php                     # Layout para auth (dark theme)
│   │   └── navigation.blade.php                # Navegação principal
│   ├── dashboard.blade.php                     # Dashboard do usuário
│   ├── home.blade.php                          # Landing page
│   ├── payment/
│   │   └── demo.blade.php                      # Tela de pagamento demo
│   ├── plans/
│   │   └── index.blade.php                     # Listagem de planos
│   └── videos/
│       └── watch.blade.php                     # Player de vídeo
├── routes/
│   └── web.php                                 # Todas as rotas da aplicação
└── bootstrap/
    └── app.php                                 # Configuração de middlewares
```

---

## 🗄️ ESTRUTURA DO BANCO DE DADOS

### Tabela: `users`
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | bigint | PK |
| name | string | Nome do usuário |
| email | string | Email (único) |
| password | string | Senha hash |
| is_admin | boolean | Se é administrador (default: false) |
| phone | string | Telefone (nullable) |
| timestamps | - | created_at, updated_at |

### Tabela: `tabs` (NOVO)
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | bigint | PK |
| name | string | Nome da aba (ex: "Treinos Iniciantes") |
| slug | string | URL amigável |
| description | text | Descrição da aba |
| icon | string | Emoji/ícone |
| order | integer | Ordem de exibição |
| is_active | boolean | Se está visível |
| timestamps | - | created_at, updated_at |

### Tabela: `plans`
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | bigint | PK |
| name | string | Nome do plano (ex: "Mensal") |
| slug | string | URL amigável (ex: "mensal") |
| description | text | Descrição do plano |
| price | decimal(10,2) | Preço em reais |
| **type** | enum | **single** (único) ou **monthly** (mensal) |
| duration_days | integer | Duração em dias |
| features | json | Lista de benefícios |
| is_active | boolean | Se está ativo |
| is_featured | boolean | Se é destaque |
| timestamps | - | created_at, updated_at |

### Tabela: `subscriptions`
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | bigint | PK |
| user_id | bigint | FK para users |
| plan_id | bigint | FK para plans |
| payment_id | string | ID do pagamento no Mercado Pago |
| payment_status | enum | pending, approved, failed, refunded |
| amount_paid | decimal(10,2) | Valor pago |
| starts_at | datetime | Início da assinatura |
| expires_at | datetime | Expiração da assinatura |
| timestamps | - | created_at, updated_at |

### Tabela: `videos`
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | bigint | PK |
| **tab_id** | bigint | FK para tabs (nullable) - aba onde o vídeo aparece |
| user_id | bigint | FK para users (nullable) - vídeos personalizados |
| title | string | Título do vídeo |
| slug | string | URL amigável |
| description | text | Descrição |
| thumbnail | string | Caminho da thumbnail |
| video_path | string | Caminho do vídeo (local) |
| video_url | string | URL externa (YouTube, Vimeo) |
| video_source | enum | local, external |
| duration_seconds | integer | Duração em segundos |
| category | string | Categoria do treino |
| order | integer | Ordem de exibição |
| is_active | boolean | Se está ativo |
| is_free | boolean | Se é gratuito |
| views_count | integer | Contador de views |
| timestamps | - | created_at, updated_at |

---

## 🔐 SISTEMA DE AUTENTICAÇÃO E AUTORIZAÇÃO

### Middlewares Customizados

1. **`EnsureUserHasActiveAccess`** (alias: `subscribed`)
   - Arquivo: `app/Http/Middleware/EnsureUserHasActiveAccess.php`
   - Verifica se o usuário tem uma assinatura ativa (não expirada e aprovada)
   - Usado para proteger rotas de vídeos premium

2. **`EnsureUserIsAdmin`** (alias: `admin`)
   - Arquivo: `app/Http/Middleware/EnsureUserIsAdmin.php`
   - Verifica se `$user->is_admin === true`
   - Usado para proteger todo o painel administrativo

### Registro dos Middlewares
Arquivo: `bootstrap/app.php`
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        'subscribed' => \App\Http\Middleware\EnsureUserHasActiveAccess::class,
    ]);
})
```

---

## 🛣️ ROTAS DA APLICAÇÃO

### Rotas Públicas
| Método | URI | Controller | Descrição |
|--------|-----|------------|-----------|
| GET | / | HomeController@index | Landing page |
| GET | /planos | PlanController@index | Lista de planos |

### Rotas Autenticadas (middleware: `auth`)
| Método | URI | Controller | Descrição |
|--------|-----|------------|-----------|
| GET | /dashboard | DashboardController@index | Dashboard do usuário |
| GET | /minhas-assinaturas | DashboardController@subscriptions | Histórico |
| POST | /checkout/{plan} | PlanController@checkout | Iniciar compra |
| GET | /pagamento/demo/{subscription} | PaymentController@demo | Tela demo |
| POST | /pagamento/demo/{subscription}/confirmar | PaymentController@confirmDemo | Confirmar demo |

### Rotas de Vídeos (middleware: `auth` + verificação interna)
| Método | URI | Controller | Descrição |
|--------|-----|------------|-----------|
| GET | /videos/{video} | DashboardController@watch | Assistir vídeo |
| GET | /videos/{video}/stream | DashboardController@stream | Stream protegido |

### Rotas Admin (middleware: `auth`, `admin`)
| Método | URI | Controller | Descrição |
|--------|-----|------------|-----------|
| GET | /admin | AdminDashboardController@index | Dashboard |
| RESOURCE | /admin/videos | Admin\VideoController | CRUD vídeos |
| RESOURCE | /admin/planos | Admin\PlanController | CRUD planos |
| GET | /admin/usuarios | Admin\UserController@index | Listar usuários |
| GET | /admin/usuarios/{user} | Admin\UserController@show | Ver usuário |
| POST | /admin/usuarios/{user}/grant | Admin\UserController@grantAccess | Dar acesso |
| POST | /admin/usuarios/{user}/revoke | Admin\UserController@revokeAccess | Remover acesso |

---

## 💳 SISTEMA DE PAGAMENTO (Mercado Pago)

### Fluxo de Pagamento
1. Usuário clica em "Assinar" em um plano
2. `PlanController@checkout` cria uma `Subscription` com status `pending`
3. `PaymentService@createCheckoutSession` cria uma Preference no Mercado Pago
4. Usuário é redirecionado para página de pagamento do Mercado Pago
5. Após pagar (PIX, Cartão ou Boleto), é redirecionado de volta
6. Webhook do Mercado Pago confirma pagamento e ativa assinatura

### Modo Demo (sem credenciais)
Se `MERCADO_PAGO_ACCESS_TOKEN` estiver vazio:
- Redireciona para `/pagamento/demo/{subscription}`
- Usuário clica em "Simular Pagamento Aprovado"
- Assinatura é ativada instantaneamente

### Configuração do Mercado Pago
1. Criar conta em [Mercado Pago Developers](https://www.mercadopago.com.br/developers/panel/app)
2. Criar aplicação e obter credenciais
3. Configurar `.env`:
```env
MERCADO_PAGO_ACCESS_TOKEN=APP_USR-xxx...
MERCADO_PAGO_PUBLIC_KEY=APP_USR-xxx...
MERCADO_PAGO_WEBHOOK_SECRET=opcional
```
4. Em Sandbox, usar credenciais de teste
5. Configurar webhook apontando para `/webhook/payment`

### Formas de Pagamento Suportadas
- PIX (instantâneo)
- Cartão de Crédito (até 12x)
- Boleto Bancário

---

## 👤 CREDENCIAIS PADRÃO (Seeders)

### Administrador
- **Email:** admin@adritreinos.com
- **Senha:** admin123
- **is_admin:** true

### Usuário Teste
- **Email:** teste@teste.com
- **Senha:** teste123
- **is_admin:** false

### Planos Criados
| Nome | Preço | Tipo | Duração |
|------|-------|------|---------|
| Mensal | R$ 49,90 | monthly | 30 dias |
| Trimestral | R$ 119,90 | single | 90 dias |
| Anual | R$ 397,00 | single | 365 dias |

### Abas Criadas
| Nome | Ícone | Descrição |
|------|-------|-----------|
| Treinos Iniciantes | 🌱 | Para quem está começando |
| Treinos Intermediários | 💪 | Base de condicionamento |
| Treinos Avançados | 🔥 | Desafios intensos |
| HIIT | ⚡ | Alta intensidade |
| Alongamentos | 🧘 | Flexibilidade |

### Vídeos de Exemplo
- 8 vídeos criados
- 2 gratuitos (is_free = true)
- 6 premium (is_free = false)
- Categorias: Aquecimento, Treino Completo, HIIT, Glúteos, Alongamento

---

## 🔧 SERVICES (CAMADA DE NEGÓCIO)

### SubscriptionService
Arquivo: `app/Services/SubscriptionService.php`

```php
// Criar assinatura pendente (após iniciar checkout)
createPending(User $user, Plan $plan, ?string $paymentId = null): Subscription

// Ativar assinatura (após pagamento confirmado)
activate(Subscription $subscription): Subscription

// Marcar como falha
fail(Subscription $subscription): Subscription

// Renovar assinatura existente
renew(Subscription $subscription): Subscription

// Verificar se usuário pode acessar conteúdo premium
canAccessPremiumContent(User $user): bool
```

### PaymentService
Arquivo: `app/Services/PaymentService.php`

```php
// Criar sessão de checkout (preparado para Stripe)
createCheckoutSession(Subscription $subscription): array

// Processar webhook do gateway
handleWebhook(array $payload): bool

// Ativar pagamento em modo demo
activateDemo(Subscription $subscription): Subscription
```

---

## 📱 VIEWS PRINCIPAIS

### Landing Page (`home.blade.php`)
- Hero section com CTA
- Seção de benefícios (4 cards)
- Seção de planos (3 cards com destaque)
- Footer

### Dashboard do Usuário (`dashboard.blade.php`)
- Status da assinatura (ativa/inativa)
- Grid de vídeos organizados por categoria
- Vídeos gratuitos sempre acessíveis
- Vídeos premium com lock se não tiver assinatura

### Player de Vídeo (`videos/watch.blade.php`)
- Player responsivo
- Suporte a vídeos locais e externos (YouTube, Vimeo)
- Lista de vídeos relacionados na lateral
- Proteção contra download direto

### Painel Admin (`admin/dashboard.blade.php`)
- Cards de métricas (usuários, vídeos, assinaturas, receita)
- Ações rápidas
- Lista de assinaturas recentes

---

## ⚡ COMANDOS ÚTEIS

```bash
# Instalar dependências
composer install
npm install

# Rodar migrations e seeders
php artisan migrate:fresh --seed

# Iniciar servidor de desenvolvimento
php artisan serve

# Iniciar Vite (em outro terminal)
npm run dev

# Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Criar link do storage
php artisan storage:link
```

---

## 🚨 PONTOS DE ATENÇÃO PARA MODIFICAÇÕES

1. **Adicionar novo campo ao usuário:**
   - Criar migration
   - Atualizar `$fillable` em `User.php`
   - Atualizar seeders se necessário

2. **Adicionar nova categoria de vídeo:**
   - Apenas adicionar nos seeders ou pelo admin
   - Não há tabela de categorias (é string livre)

3. **Modificar tema/cores:**
   - Cores estão inline nas views (classes Tailwind)
   - Padrão: `bg-gray-900`, `bg-gray-800`, `text-red-600`, `bg-red-600`

4. **Adicionar gateway de pagamento real:**
   - Editar `PaymentService.php`
   - Adicionar rotas de webhook em `web.php`
   - Configurar `.env`

5. **Adicionar novos campos aos vídeos:**
   - Migration para adicionar coluna
   - Atualizar `Video.php` ($fillable, accessors se necessário)
   - Atualizar `VideoRequest.php` (validação)
   - Atualizar views de create/edit

---

## 📄 ARQUIVOS DE CONFIGURAÇÃO

- **`.env`** - Variáveis de ambiente (não commitado)
- **`config/app.php`** - Configuração da aplicação
- **`tailwind.config.js`** - Configuração do Tailwind
- **`vite.config.js`** - Configuração do Vite

---

## 🏷️ VERSÃO E DATA

- **Versão:** 1.0.0
- **Data de criação:** 21/01/2026
- **Laravel:** 12.x
- **PHP:** 8.2+

---

*Este arquivo foi criado para facilitar a continuidade do desenvolvimento por IA ou desenvolvedores que não conhecem o projeto.*
