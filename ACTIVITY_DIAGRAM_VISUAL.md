# Nusantara Daily News - Visual Flowchart (Mermaid Format)

Paste these diagrams into [Mermaid Live Editor](https://mermaid.live/) for visualization.

---

## 1. Main System Overview (All Swimlanes)

```mermaid
graph TD
    Start([Start]) --> CheckAuth{User Logged In?}
    
    CheckAuth -->|No| Reader["🔓 READER<br/>(No Login)"]
    CheckAuth -->|Yes| CheckRole{Is Admin?}
    CheckRole -->|No| User["👤 USER<br/>(Authenticated)"]
    CheckRole -->|Yes| Admin["⚙️ ADMIN<br/>(Moderator)"]
    
    Reader --> ReaderOpts["▶ Home<br/>▶ Search<br/>▶ Read Article<br/>▶ View Comments<br/>▶ Try to Like → Login"]
    User --> UserOpts["▶ Home<br/>▶ Submit News<br/>▶ My Articles<br/>▶ Like/Comment<br/>▶ Notifications<br/>▶ Profile"]
    Admin --> AdminOpts["▶ Dashboard<br/>▶ Verify Articles<br/>▶ Manage Published<br/>▶ Manage Categories<br/>▶ Manage Trash"]
    
    ReaderOpts --> DB[(Database)]
    UserOpts --> DB
    AdminOpts --> DB
```

---

## 2. Reader Flow (No Login)

```mermaid
graph TD
    Reader[🔓 Reader: Non-authenticated User]
    
    Reader --> Home["📰 View Home Page"]
    Home --> Featured["⭐ See Featured Article"]
    Home --> List["📋 See Article List"]
    
    Featured --> Detail["📄 Click Article"]
    List --> Detail
    
    Detail --> Read["📖 Read Full Content"]
    Read --> Comments["💬 View Comments"]
    
    Comments --> Like["❤️ Try to Like"]
    Like --> Decision{Like Button?}
    Decision -->|Clicked| Prompt["🔒 Prompt: Login Required"]
    Prompt --> Login["🔐 Go to Login Page"]
    
    Detail --> Search["🔍 Use Search"]
    Search --> Results["📊 View Results"]
    Results --> Detail
    
    Detail --> Filter["🏷️ Filter by Category"]
    Filter --> Sort["📈 Sort: Latest/Popular"]
    Sort --> Detail
```

---

## 3. Authenticated User Flow

```mermaid
graph TD
    User["👤 Authenticated User<br/>(After Login)"]
    
    User --> Dashboard["🏠 User Dashboard"]
    
    Dashboard --> Action{Choose Action}
    
    Action -->|Submit News| Submit["✍️ Go to Submit Page"]
    Submit --> Form["📝 Fill Form"]
    Form --> Title["📌 Enter Title"]
    Form --> Category["🏷️ Select Category"]
    Form --> Content["📝 Write Content"]
    Form --> Image["🖼️ Upload Image + Crop"]
    
    Title --> Validation{Valid?}
    Validation -->|No| Error["❌ Show Errors"]
    Error --> Form
    Validation -->|Yes| Submit2["✅ Submit Article"]
    Submit2 --> Save["💾 System: Save as PENDING"]
    Save --> Notify["🔔 Notify Admin for Review"]
    
    Action -->|View My Articles| MyArts["📄 Show My Articles List"]
    MyArts --> Status["📊 View Status & Stats"]
    Status --> Action2{Article Status?}
    Action2 -->|Pending/Rejected| Delete["🗑️ Can Delete"]
    Action2 -->|Approved| View["👁️ Can View Published"]
    
    Action -->|Like/Comment| Interact["💬 Interact with Content"]
    Interact --> LikeBtn["❤️ Click Like"]
    LikeBtn --> Save2["💾 System: Record Like"]
    Interact --> Comment["💭 Write Comment"]
    Comment --> Reply["↩️ Reply to Comment"]
    Reply --> Save3["💾 System: Save Reply"]
    
    Action -->|Notifications| Notif["🔔 View Notifications"]
    Notif --> NotifType{Notification Type}
    NotifType -->|Article Approved| NApp["✅ Article Approved"]
    NotifType -->|Article Rejected| NRej["❌ Article Rejected"]
    NotifType -->|New Like| NLike["❤️ Got New Like"]
    NotifType -->|New Comment| NComment["💬 Got New Comment"]
    
    Action -->|Profile| Profile["👤 Edit Profile"]
    Profile --> Update["🔄 Update Info/Password"]
    Profile --> Delete["🗑️ Delete Account"]
```

---

## 4. Admin Flow

```mermaid
graph TD
    Admin["⚙️ Admin Dashboard<br/>(Moderator)"]
    
    Admin --> Overview["📊 View Overview Stats"]
    Overview --> Stats["Total: Articles, Users,<br/>Published, Pending, Likes, Views"]
    
    Admin --> Verify["✅ Article Verification"]
    Verify --> Pending["📋 See Pending Articles"]
    Pending --> Review{Review Article}
    
    Review -->|Approve| Approve["✔️ Click Approve"]
    Approve --> SysApprove["💾 System: Status → APPROVED"]
    SysApprove --> NotifyAuth["🔔 Notify Author: Approved"]
    NotifyAuth --> Visible["👁️ Article Now Visible"]
    
    Review -->|Reject| Reject["❌ Click Reject"]
    Reject --> SysReject["💾 System: Status → TRASHED"]
    SysReject --> NotifyRej["🔔 Notify Author: Rejected"]
    
    Admin --> Manage["📌 Manage Published"]
    Manage --> Featured["⭐ Set as Featured"]
    Featured --> Hero["🎨 Shows in Hero Section"]
    
    Manage --> Spotlight["✨ Toggle Spotlight"]
    Manage --> Edit["✏️ Edit Article"]
    Manage --> Unpub["⬇️ Unpublish"]
    Unpub --> Hidden["🙈 Hidden from Public"]
    
    Admin --> Unpublished["📭 Manage Unpublished"]
    Unpublished --> Repub["↩️ Republish"]
    Repub --> SysRepub["💾 System: Status → APPROVED"]
    
    Admin --> Trash["🗑️ Manage Trash"]
    Trash --> Trashed["See Deleted Articles"]
    Trashed --> Action{Action}
    Action -->|Restore| Restore["↩️ Click Restore"]
    Restore --> SysRestore["💾 System: Restore Article"]
    Action -->|Delete| Perm["💀 Click Permanent Delete"]
    Perm --> SysPerm["💾 System: Delete from DB"]
    
    Admin --> Cat["🏷️ Category Management"]
    Cat --> CatList["📋 View Categories"]
    CatList --> Add["➕ Add New"]
    CatList --> Edit2["✏️ Edit"]
    CatList --> Delete2["🗑️ Delete"]
```

---

## 5. Article Status Lifecycle

```mermaid
graph LR
    NEW["🆕 NEW"] --> PEND["⏳ PENDING<br/>(Waiting Admin)"]
    
    PEND --> APPROVE{Admin<br/>Decision}
    
    APPROVE -->|Approve| APPR["✅ APPROVED<br/>(Published)"]
    APPROVE -->|Reject| TRASH["🗑️ TRASHED<br/>(Rejected)"]
    
    APPR --> UNPUB["⬇️ UNPUBLISHED<br/>(Hidden)"]
    UNPUB --> REPUB["↩️ REPUBLISH"]
    REPUB --> APPR
    
    APPR --> DELETE["💀 DELETE"]
    DELETE --> TRASH
    
    TRASH --> RESTORE["↩️ RESTORE"]
    RESTORE --> APPR
    
    TRASH --> PERM["🔥 PERMANENT<br/>DELETE"]
    PERM --> DELETED["❌ GONE"]
    
    APPR --> ACTIVE["🟢 VISIBLE<br/>ON SITE"]
    UNPUB --> HIDDEN["🔴 HIDDEN<br/>FROM PUBLIC"]
    TRASH --> HIDDEN
```

---

## 6. System Database Transactions

```mermaid
graph TD
    SYS["⚙️ SYSTEM OPERATIONS"]
    
    SYS --> AUTH["🔐 Authentication"]
    AUTH --> REG["Register: Hash password"]
    AUTH --> LOG["Login: Validate creds"]
    AUTH --> EMAIL["Email: Verify"]
    
    SYS --> ART["📄 Article Management"]
    ART --> CRT["Create: Set status PENDING"]
    ART --> UPD["Update: Modify content"]
    ART --> STS["Status: Change lifecycle"]
    ART --> VIEW["Views: Increment counter"]
    
    SYS --> INT["💬 Interactions"]
    INT --> LIKE["Like: Save ArticleLike"]
    INT --> CMT["Comment: Save + Tree"]
    INT --> REP["Reply: Link parent"]
    INT --> DEL["Delete: Remove entry"]
    
    SYS --> NOT["🔔 Notifications"]
    NOT --> NAPR["On: Article Approved"]
    NOT --> NREJ["On: Article Rejected"]
    NOT --> NLIKE["On: New Like"]
    NOT --> NCMT["On: New Comment"]
    
    SYS --> QRY["🔍 Query Operations"]
    QRY --> SRCH["Search: Title/Content"]
    QRY --> FILT["Filter: By category"]
    QRY --> SRT["Sort: Latest/Popular"]
    
    SYS --> DB[(📊 DATABASE<br/>Articles | Users | Comments<br/>Likes | Categories | Notifications)]
    
    AUTH --> DB
    ART --> DB
    INT --> DB
    NOT --> DB
    QRY --> DB
```

---

## 7. Permission Matrix

```mermaid
graph TD
    PERM["🔑 Permission Checks"]
    
    PERM --> LIKE["❤️ Can Like?"]
    LIKE --> LO{"Logged In?"}
    LO -->|No| NO1["❌ Redirect Login"]
    LO -->|Yes| YES1["✅ Record Like"]
    
    PERM --> CMT["💬 Can Comment?"]
    CMT --> CO{"Logged In?"}
    CO -->|No| NO2["❌ Redirect Login"]
    CO -->|Yes| YES2["✅ Save Comment"]
    
    PERM --> SUB["✍️ Can Submit?"]
    SUB --> SU{"Logged In?"}
    SU -->|No| NO3["❌ Redirect Login"]
    SU -->|Yes| YES3["✅ Create Pending"]
    
    PERM --> DEL["🗑️ Can Delete Article?"]
    DEL --> DE{"Owner OR<br/>Admin?"}
    DE -->|No| NO4["❌ Denied"]
    DE -->|Yes| YES4{Status<br/>≠ Approved?}
    YES4 -->|Yes| YES5["✅ Delete"]
    YES4 -->|No| NO5["❌ Denied<br/>(Published)"]
    
    PERM --> ADM["⚙️ Can Admin?"]
    ADM --> AD{"Admin<br/>Role?"}
    AD -->|No| NO6["❌ Denied"]
    AD -->|Yes| YES6["✅ Full Access"]
```

---

## 8. Search & Filter Flow

```mermaid
graph TD
    HOME["🏠 Home Page"]
    HOME --> SEARCH["🔍 Search Input"]
    SEARCH --> ENTER["User Enters Text"]
    
    HOME --> SORT["📈 Sort Dropdown"]
    SORT --> LATEST["Terbaru"]
    SORT --> OLDEST["Terlama"]
    SORT --> POPULAR["Terpopuler"]
    
    HOME --> CAT["🏷️ Category Tabs"]
    CAT --> SEL["Select Category"]
    SEL --> FILTER["Filter Articles"]
    
    ENTER --> QUERY["💾 System: Query DB"]
    LATEST --> QUERY
    OLDEST --> QUERY
    POPULAR --> QUERY
    FILTER --> QUERY
    
    QUERY --> RESULT["📊 Display Results"]
    RESULT --> PAGING["📄 Pagination"]
    PAGING --> ARTICLE["📰 Show Articles"]
```
