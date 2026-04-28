# Nusantara Daily News - Activity Diagram

## System Actors (4 Swimlanes):
1. **Reader** - Non-authenticated user (hanya baca)
2. **Authenticated User** - User yang login untuk submit/interaksi
3. **Admin** - Administrator sistem
4. **System** - Backend/Database operations

---

## Main User Flows

### 1. Reader Flow (Non-authenticated User)

```
START
  ↓
[Access Home Page]
  ↓
[View Featured Article & List]
  ↓
╱─ [Search Articles]
│   ├─ [Filter by Category] 
│   ├─ [Sort: Latest/Oldest/Popular]
│   └─ [View Results]
│
├─ [View Article Detail]
│   ├─ [Read Full Content]
│   ├─ [See Comments]
│   └─ [See Like Count]
│
└─ [Click Like Button]
    └─ [Prompted to Login]
        └─ [Redirect to Login Page]
           ├─ [Register → Fill Form → Verify Email → Login]
           └─ [Login → Enter Credentials → Authenticated]
```

---

### 2. Authenticated User Flow (Submit News)

```
START (After Login)
  ↓
[Dashboard/Home]
  ↓
[Choose Action]
  ↓
┌─────────────────────────────────┐
│                                 │
├─ [Submit News]                  ├─ [Interact with Content]
│  ├─ [Go to Submit Page]          │  ├─ [Like Article]
│  ├─ [Fill Form]                  │  │  └─ System: Record Like
│  │  ├─ Title                      │  │
│  │  ├─ Category                   │  ├─ [Comment]
│  │  ├─ Content (Editor)           │  │  ├─ [Write Comment]
│  │  └─ Image (Crop)               │  │  └─ System: Save Comment
│  ├─ [Submit]                      │  │
│  └─ System: Save as PENDING       │  ├─ [Reply to Comment]
│     └─ Send Notification to Admin │  │  └─ System: Save Reply
│                                    │
│                                    ├─ [View My Articles]
│                                    │  ├─ [View Status]
│                                    │  ├─ [View Stats]
│                                    │  │  ├─ Views
│                                    │  │  └─ Likes
│                                    │  ├─ [Delete if Status ≠ Approved]
│                                    │  └─ [View if Approved]
│                                    │
│                                    ├─ [Check Notifications]
│                                    │  ├─ [Article Approved]
│                                    │  ├─ [Article Rejected]
│                                    │  └─ [Article Got Likes/Comments]
│                                    │
│                                    └─ [Edit Profile]
│                                       ├─ [Update Info]
│                                       ├─ [Change Password]
│                                       └─ [Delete Account]
└─────────────────────────────────┘
```

---

### 3. Admin Flow (Moderation & Management)

```
START (After Login with Admin Role)
  ↓
[Admin Dashboard]
  ├─ [View Overview Stats]
  │  ├─ Total Articles
  │  ├─ Published Articles
  │  ├─ Pending Articles
  │  ├─ Total Users
  │  ├─ Total Likes
  │  └─ Total Views
  │
  ├─ [Article Verification]
  │  └─ [Go to Pending Articles]
  │     ├─ [View Article]
  │     ├─ [Approve]
  │     │  └─ System: Update Status → APPROVED
  │     │     └─ System: Send Notification to Author
  │     │        └─ System: Article Now Visible on Home
  │     │
  │     └─ [Reject]
  │        └─ System: Update Status → TRASHED
  │           └─ System: Send Notification + Reason to Author
  │
  ├─ [Manage Published Articles]
  │  ├─ [View All Published]
  │  ├─ [Set as Featured]
  │  │  └─ System: Article shows in hero section
  │  ├─ [Toggle Spotlight]
  │  │  └─ System: Article highlighted/starred
  │  ├─ [Unpublish]
  │  │  └─ System: Status → UNPUBLISHED (hidden from public)
  │  ├─ [Edit Article]
  │  │  ├─ [Update Title/Content/Image]
  │  │  └─ System: Save Changes
  │  └─ [Delete]
  │     └─ System: Move to Trash
  │
  ├─ [Manage Unpublished Articles]
  │  ├─ [View Unpublished]
  │  └─ [Republish]
  │     └─ System: Status → APPROVED (visible again)
  │
  ├─ [Manage Trash]
  │  ├─ [View Trashed Articles]
  │  ├─ [Restore]
  │  │  └─ System: Restore to Previous Status
  │  └─ [Permanent Delete]
  │     └─ System: Delete from Database
  │
  └─ [Category Management]
     ├─ [View Categories]
     ├─ [Add New Category]
     │  └─ System: Save Category
     ├─ [Edit Category]
     │  └─ System: Update Category
     └─ [Delete Category]
        └─ System: Remove Category (if no articles)
```

---

### 4. System Operations

```
┌─────────────────────────────────────────┐
│         SYSTEM/BACKEND FLOWS            │
├─────────────────────────────────────────┤
│                                         │
│ ▶ Authentication                        │
│   ├─ Register: Validate → Hash Password │
│   ├─ Login: Validate Credentials       │
│   ├─ Email Verification                │
│   └─ Session Management                │
│                                         │
│ ▶ Article Management                    │
│   ├─ Create: Save with PENDING status  │
│   ├─ Update: Modify content/metadata   │
│   ├─ Status Changes: PENDING→APPROVED→ │
│   │  UNPUBLISHED→TRASHED→DELETED       │
│   ├─ Featured/Spotlight Toggle         │
│   └─ View Count Increment              │
│                                         │
│ ▶ Interactions                          │
│   ├─ Like: Record ArticleLike          │
│   ├─ Comment: Save Comment Tree        │
│   ├─ Reply: Link to Parent Comment     │
│   └─ Delete: Remove or Soft Delete     │
│                                         │
│ ▶ Notifications                         │
│   ├─ On Article Action (Approve/Reject)│
│   ├─ On New Like                       │
│   ├─ On New Comment/Reply              │
│   └─ Mark as Read/Unread               │
│                                         │
│ ▶ Search & Filter                       │
│   ├─ Search by Title/Content           │
│   ├─ Filter by Category                │
│   ├─ Sort: Latest/Oldest/Popular      │
│   └─ Pagination                        │
│                                         │
│ ▶ Data Retrieval                        │
│   ├─ Home: Featured + Popular + All    │
│   ├─ Detail: Full Article + Comments  │
│   ├─ Admin Dashboard: Stats & Reports │
│   └─ User Dashboard: My Articles       │
│                                         │
└─────────────────────────────────────────┘
```

---

## Swimlane Interaction Matrix

| Action | Reader | User | Admin | System |
|--------|--------|------|-------|--------|
| View Home | ✓ | ✓ | ✓ | Fetch articles |
| Read Article | ✓ | ✓ | ✓ | Increment views |
| Search/Filter | ✓ | ✓ | ✓ | Query database |
| Like Article | - | ✓ | - | Save like |
| Comment | - | ✓ | - | Save comment |
| Submit News | - | ✓ | - | Create (pending) |
| View My Articles | - | ✓ | - | Fetch user's articles |
| Approve/Reject | - | - | ✓ | Update status |
| Manage Categories | - | - | ✓ | CRUD categories |
| Manage Trash | - | - | ✓ | Restore/Delete |
| View Notifications | - | ✓ | - | Fetch notifications |

---

## Article Status Flow

```
[NEW ARTICLE]
    ↓
[PENDING] ← User submits article
    ↓
    ├─→ [APPROVED] ← Admin approves → [PUBLISHED & VISIBLE]
    │       ↓
    │   [UNPUBLISHED] ← Admin unpublishes (hidden from public)
    │       ↓
    │   [REPUBLISH] → [APPROVED]
    │
    └─→ [TRASHED/REJECTED] ← Admin rejects → [HIDDEN]
        ↓
        ├─ [RESTORED] → [APPROVED] (if restored)
        └─ [PERMANENTLY DELETED] → [GONE]
```

---

## Key Decision Points

1. **User Wants to Like/Comment?**
   - Yes & Not Logged In → Redirect to Login
   - Yes & Logged In → Allow action

2. **User Submits Article?**
   - Valid Form → Save as PENDING → Notify Admin
   - Invalid Form → Show Errors

3. **Admin Reviews Article?**
   - Approve → Status APPROVED → Notify Author → Visible
   - Reject → Status TRASHED → Notify Author → Hidden

4. **View Count Logic?**
   - Each article view increments counter (system records)

5. **Permission Check?**
   - Delete article only if: (Owner AND status ≠ APPROVED) OR Admin
   - Edit profile only if: Owner of profile

---

## Integration Points

- **Authentication**: Laravel Auth middleware
- **Database**: Articles, Users, Categories, Comments, Likes, Notifications
- **Notifications**: Trigger on key actions (approve, reject, like, comment)
- **Storage**: Image upload (with cropping on frontend)
- **Search**: Full-text search on title & content
- **Pagination**: For article lists & comments
