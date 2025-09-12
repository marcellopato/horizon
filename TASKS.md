# HORIZON - Hireflix Clone Tasks

## 📋 Projeto Overview
Desenvolver um clone básico do Hireflix (aplicativo de entrevistas em vídeo) usando Laravel + TailwindCSS + MySQL dentro de 24 horas.

---

## 🎯 Core Features Required

### 1. Sistema de Autenticação
- [ ] Setup Laravel Authentication (Breeze/Jetstream)
- [ ] Criar roles: Admin/Reviewer e Candidate
- [ ] Middleware para controle de acesso
- [ ] Páginas de login/registro
- [ ] Profile management

### 2. Admin/Reviewer Features
- [ ] Dashboard para Admin/Reviewer
- [ ] Criar entrevistas (título, descrição, perguntas)
- [ ] Gerenciar entrevistas existentes
- [ ] Visualizar lista de candidatos
- [ ] Sistema de avaliação (scores/comentários)

### 3. Candidate Features
- [ ] Dashboard para Candidatos
- [ ] Visualizar entrevistas disponíveis
- [ ] Gravar respostas em vídeo para cada pergunta
- [ ] Upload de vídeos
- [ ] Visualizar status das submissões

### 4. Video Recording & Upload
- [ ] Implementar gravação de vídeo no browser (MediaRecorder API)
- [ ] Sistema de upload de arquivos
- [ ] Storage de vídeos (local/cloud)
- [ ] Player de vídeo para review

---

## 🔧 Technical Setup

### Environment & Infrastructure
- [ ] **Docker Setup**
  - [ ] Dockerfile para Laravel
  - [ ] docker-compose.yml (Laravel + MySQL + Redis)
  - [ ] Configurar volumes para desenvolvimento
  - [ ] Scripts de inicialização

### Database Design
- [ ] **Users Table** (com role field)
- [ ] **Interviews Table** (título, descrição, created_by)
- [ ] **Questions Table** (pergunta, interview_id, order)
- [ ] **Submissions Table** (candidate_id, interview_id, status)
- [ ] **Answers Table** (submission_id, question_id, video_path)
- [ ] **Reviews Table** (submission_id, reviewer_id, score, comments)

### Frontend Setup
- [ ] Instalar e configurar TailwindCSS
- [ ] Criar layouts base (admin/candidate)
- [ ] Components reutilizáveis
- [ ] Responsividade mobile

---

## 🚀 Development Workflow

### Phase 1: Foundation (Hours 1-4)
- [ ] Setup Docker environment
- [ ] Configure Laravel + MySQL + TailwindCSS
- [ ] Create database migrations
- [ ] Setup authentication system
- [ ] Create basic layouts and navigation

### Phase 2: Core Functionality (Hours 5-12)
- [ ] Interview creation system
- [ ] Question management
- [ ] Video recording interface
- [ ] File upload system
- [ ] Basic review system

### Phase 3: Polish & Testing (Hours 13-20)
- [ ] UI improvements
- [ ] Error handling
- [ ] Validation
- [ ] Basic testing
- [ ] Seed data for demo

### Phase 4: Documentation & Demo (Hours 21-24)
- [ ] Write comprehensive README
- [ ] Create test accounts
- [ ] Record Loom video
- [ ] Final deployment/demo setup

---

## 📦 Deliverables Checklist

### Code & Repository
- [ ] Clean, well-organized code
- [ ] Meaningful commit messages
- [ ] Proper branching strategy
- [ ] All features working end-to-end

### Documentation
- [ ] **README.md** with:
  - [ ] Project overview
  - [ ] Installation instructions (Docker)
  - [ ] How to run locally
  - [ ] Test account credentials
  - [ ] Known limitations
  - [ ] Technology stack used

### Demo Requirements
- [ ] **Loom Video (3-8 minutes)** showing:
  - [ ] Sign up/sign in process
  - [ ] Admin creating an interview with questions
  - [ ] Candidate recording and uploading video answers
  - [ ] Reviewer viewing submissions and leaving scores/comments
  - [ ] Brief code walkthrough

### Test Accounts
- [ ] Admin/Reviewer account
- [ ] Candidate account
- [ ] Sample interviews with questions
- [ ] Sample submissions for review

---

## 🧪 Testing Strategy

### Manual Testing
- [ ] User registration/login flows
- [ ] Interview creation and management
- [ ] Video recording and upload
- [ ] Review and scoring system
- [ ] Cross-browser compatibility
- [ ] Mobile responsiveness

### Automated Testing (if time permits)
- [ ] Unit tests for models
- [ ] Feature tests for main workflows
- [ ] Authentication tests
- [ ] File upload tests

---

## 📱 Technical Considerations

### Video Handling
- [ ] Max file size limits
- [ ] Supported video formats
- [ ] Compression/optimization
- [ ] Playback compatibility

### Security
- [ ] File upload validation
- [ ] XSS protection
- [ ] CSRF tokens
- [ ] Proper authorization checks

### Performance
- [ ] Database indexing
- [ ] Query optimization
- [ ] Asset optimization
- [ ] Caching strategy (if needed)

---

## 🎨 UI/UX Features

### Must-Have
- [ ] Clean, professional design
- [ ] Intuitive navigation
- [ ] Clear user feedback
- [ ] Loading states
- [ ] Error messages

### Nice-to-Have (if time permits)
- [ ] Progress indicators
- [ ] Drag & drop upload
- [ ] Video thumbnails
- [ ] Advanced filtering/search
- [ ] Email notifications

---

## 📋 Final Submission

### Before Submitting
- [ ] All core features working
- [ ] No critical bugs
- [ ] README is complete and accurate
- [ ] Test accounts are working
- [ ] Loom video is recorded and uploaded
- [ ] Code is pushed to GitHub

### Submission Items
- [ ] GitHub repository link
- [ ] Loom video link
- [ ] Live demo URL (if deployed)
- [ ] Any additional notes or limitations

---

**Target Completion: 24 hours**
**Focus: Core functionality first, polish second**
**Remember: It's better to have fewer features working perfectly than many features working poorly**