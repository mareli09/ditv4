from docx import Document
from docx.shared import Pt, RGBColor, Inches, Cm
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml.ns import qn
from docx.oxml import OxmlElement
import copy

doc = Document()

# ── Page margins ─────────────────────────────────────────
for section in doc.sections:
    section.top_margin    = Cm(2.5)
    section.bottom_margin = Cm(2.5)
    section.left_margin   = Cm(3.0)
    section.right_margin  = Cm(2.5)

# ── Color palette ────────────────────────────────────────
DARK_BLUE  = RGBColor(0x1A, 0x3A, 0x5C)
MID_BLUE   = RGBColor(0x2E, 0x6D, 0xA4)
ACCENT     = RGBColor(0x0A, 0x9E, 0x8C)
LIGHT_GRAY = RGBColor(0xF2, 0xF4, 0xF7)
WHITE      = RGBColor(0xFF, 0xFF, 0xFF)
TEXT_DARK  = RGBColor(0x1E, 0x1E, 0x2E)

# ── Helpers ──────────────────────────────────────────────
def set_cell_bg(cell, hex_color):
    tc   = cell._tc
    tcPr = tc.get_or_add_tcPr()
    shd  = OxmlElement('w:shd')
    shd.set(qn('w:val'),   'clear')
    shd.set(qn('w:color'), 'auto')
    shd.set(qn('w:fill'),  hex_color)
    tcPr.append(shd)

def set_cell_border(cell, **kwargs):
    tc   = cell._tc
    tcPr = tc.get_or_add_tcPr()
    tcBorders = OxmlElement('w:tcBorders')
    for edge in ('top','left','bottom','right'):
        tag = OxmlElement(f'w:{edge}')
        tag.set(qn('w:val'),  kwargs.get('val',  'single'))
        tag.set(qn('w:sz'),   kwargs.get('sz',   '4'))
        tag.set(qn('w:color'),kwargs.get('color','CCCCCC'))
        tcBorders.append(tag)
    tcPr.append(tcBorders)

def heading1(text):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(18)
    p.paragraph_format.space_after  = Pt(6)
    run = p.add_run(text)
    run.bold      = True
    run.font.size = Pt(16)
    run.font.color.rgb = DARK_BLUE
    # bottom border
    pPr  = p._p.get_or_add_pPr()
    pBdr = OxmlElement('w:pBdr')
    bottom = OxmlElement('w:bottom')
    bottom.set(qn('w:val'),   'single')
    bottom.set(qn('w:sz'),    '6')
    bottom.set(qn('w:color'), '2E6DA4')
    pBdr.append(bottom)
    pPr.append(pBdr)
    return p

def heading2(text):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(12)
    p.paragraph_format.space_after  = Pt(4)
    run = p.add_run(text)
    run.bold      = True
    run.font.size = Pt(13)
    run.font.color.rgb = MID_BLUE
    return p

def heading3(text):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(8)
    p.paragraph_format.space_after  = Pt(2)
    run = p.add_run(text)
    run.bold      = True
    run.font.size = Pt(11)
    run.font.color.rgb = ACCENT
    return p

def body(text, bold=False, italic=False):
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(4)
    run = p.add_run(text)
    run.bold        = bold
    run.italic      = italic
    run.font.size   = Pt(10.5)
    run.font.color.rgb = TEXT_DARK
    return p

def bullet(text, level=0):
    p = doc.add_paragraph(style='List Bullet')
    p.paragraph_format.left_indent  = Inches(0.25 + level * 0.25)
    p.paragraph_format.space_after  = Pt(2)
    run = p.add_run(text)
    run.font.size      = Pt(10.5)
    run.font.color.rgb = TEXT_DARK
    return p

def numbered(text, level=0):
    p = doc.add_paragraph(style='List Number')
    p.paragraph_format.left_indent = Inches(0.25 + level * 0.25)
    p.paragraph_format.space_after = Pt(2)
    run = p.add_run(text)
    run.font.size      = Pt(10.5)
    run.font.color.rgb = TEXT_DARK
    return p

def note_box(text):
    tbl  = doc.add_table(rows=1, cols=1)
    cell = tbl.cell(0,0)
    set_cell_bg(cell, 'E8F4FD')
    set_cell_border(cell, val='single', sz='4', color='2E6DA4')
    cell.paragraphs[0].clear()
    p   = cell.paragraphs[0]
    p.paragraph_format.left_indent = Inches(0.1)
    run = p.add_run('ℹ  ' + text)
    run.font.size      = Pt(10)
    run.font.color.rgb = MID_BLUE
    doc.add_paragraph()

def warning_box(text):
    tbl  = doc.add_table(rows=1, cols=1)
    cell = tbl.cell(0,0)
    set_cell_bg(cell, 'FFF3CD')
    set_cell_border(cell, val='single', sz='4', color='FFA500')
    cell.paragraphs[0].clear()
    p   = cell.paragraphs[0]
    p.paragraph_format.left_indent = Inches(0.1)
    run = p.add_run('⚠  ' + text)
    run.font.size      = Pt(10)
    run.font.color.rgb = RGBColor(0x85, 0x64, 0x04)
    doc.add_paragraph()

def styled_table(headers, rows, col_widths=None):
    tbl = doc.add_table(rows=1 + len(rows), cols=len(headers))
    tbl.style = 'Table Grid'
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER

    # Header row
    hdr = tbl.rows[0]
    for i, h in enumerate(headers):
        cell = hdr.cells[i]
        set_cell_bg(cell, '1A3A5C')
        set_cell_border(cell, val='single', sz='4', color='1A3A5C')
        cell.paragraphs[0].clear()
        run = cell.paragraphs[0].add_run(h)
        run.bold            = True
        run.font.size       = Pt(10)
        run.font.color.rgb  = WHITE
        cell.paragraphs[0].alignment = WD_ALIGN_PARAGRAPH.CENTER
        cell.vertical_alignment = WD_ALIGN_VERTICAL.CENTER

    # Data rows
    for ri, row in enumerate(rows):
        bg = 'F8FAFC' if ri % 2 == 0 else 'FFFFFF'
        for ci, val in enumerate(row):
            cell = tbl.rows[ri+1].cells[ci]
            set_cell_bg(cell, bg)
            set_cell_border(cell, val='single', sz='2', color='DDDDDD')
            cell.paragraphs[0].clear()
            run = cell.paragraphs[0].add_run(str(val))
            run.font.size      = Pt(10)
            run.font.color.rgb = TEXT_DARK
            cell.vertical_alignment = WD_ALIGN_VERTICAL.CENTER

    if col_widths:
        for i, w in enumerate(col_widths):
            for row in tbl.rows:
                row.cells[i].width = Inches(w)

    doc.add_paragraph()
    return tbl

def page_break():
    doc.add_page_break()

# ════════════════════════════════════════════════════════════
# COVER PAGE
# ════════════════════════════════════════════════════════════
cover = doc.add_paragraph()
cover.alignment = WD_ALIGN_PARAGRAPH.CENTER
cover.paragraph_format.space_before = Pt(60)

run = cover.add_run('CESO PORTAL')
run.bold            = True
run.font.size       = Pt(32)
run.font.color.rgb  = DARK_BLUE

p2 = doc.add_paragraph()
p2.alignment = WD_ALIGN_PARAGRAPH.CENTER
run2 = p2.add_run('Community Extension Services System')
run2.bold           = True
run2.font.size      = Pt(18)
run2.font.color.rgb = MID_BLUE

doc.add_paragraph()
p3 = doc.add_paragraph()
p3.alignment = WD_ALIGN_PARAGRAPH.CENTER
run3 = p3.add_run('USER MANUAL')
run3.bold           = True
run3.font.size      = Pt(22)
run3.font.color.rgb = ACCENT

doc.add_paragraph()
doc.add_paragraph()

for line in [
    'Version 2.0',
    'Far Eastern University Institute of Technology',
    'La Consolacion University',
    'Last Updated: May 2026',
]:
    lp = doc.add_paragraph()
    lp.alignment = WD_ALIGN_PARAGRAPH.CENTER
    lr = lp.add_run(line)
    lr.font.size      = Pt(11)
    lr.font.color.rgb = RGBColor(0x55, 0x55, 0x66)

page_break()

# ════════════════════════════════════════════════════════════
# TABLE OF CONTENTS  (static — Word updates it on F9)
# ════════════════════════════════════════════════════════════
heading1('Table of Contents')
toc_items = [
    ('1.', 'System Overview',                              '3'),
    ('2.', 'User Roles Summary',                           '3'),
    ('3.', 'Getting Started',                              '4'),
    ('  3.1', 'Accessing the Portal',                      '4'),
    ('  3.2', 'Community User Registration',               '4'),
    ('  3.3', 'Login',                                     '5'),
    ('  3.4', 'Email Verification',                        '5'),
    ('  3.5', 'Forgot & Reset Password',                   '5'),
    ('4.', 'IT Administrator Guide',                       '6'),
    ('  4.1', 'Dashboard',                                 '6'),
    ('  4.2', 'User Management',                           '6'),
    ('  4.3', 'Bulk CSV Import',                           '7'),
    ('  4.4', 'Profile Settings',                          '7'),
    ('5.', 'CESO Staff Guide',                             '8'),
    ('  5.1', 'AI Dashboard',                              '8'),
    ('  5.2', 'Activity Management',                       '8'),
    ('  5.3', 'Project Management',                        '10'),
    ('  5.4', 'Announcement Management',                   '11'),
    ('  5.5', 'Website Content Management (CMS)',          '11'),
    ('  5.6', 'Sentiment Analysis & Analytics',            '12'),
    ('  5.7', 'Profile Settings',                          '13'),
    ('6.', 'Student Guide',                                '13'),
    ('7.', 'Faculty Guide',                                '14'),
    ('8.', 'Institutional Staff Guide',                    '15'),
    ('9.', 'Community User Guide',                         '16'),
    ('10.','AI-Powered Features',                          '17'),
    ('11.','Troubleshooting',                              '18'),
    ('12.','Frequently Asked Questions',                   '19'),
    ('13.','Quick Reference',                              '20'),
]
for num, title, pg in toc_items:
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(2)
    r1 = p.add_run(f'{num}  {title}')
    r1.font.size = Pt(10.5)
    r1.font.color.rgb = TEXT_DARK
    r2 = p.add_run(f'  {"." * max(1, 60 - len(num) - len(title))}  {pg}')
    r2.font.size      = Pt(10)
    r2.font.color.rgb = RGBColor(0x99, 0x99, 0x99)

page_break()

# ════════════════════════════════════════════════════════════
# 1. SYSTEM OVERVIEW
# ════════════════════════════════════════════════════════════
heading1('1. System Overview')
body(
    'The CESO Portal is a web-based information system designed to manage the full lifecycle '
    'of community extension services at higher education institutions. It connects five user '
    'groups — IT Administrators, CESO Staff, Students, Faculty, and Community Members — '
    'through a unified platform that handles activity planning, participant registration, '
    'feedback collection, AI-powered sentiment analysis, and institutional reporting.'
)
body('The system is accessible at: www.ceso.me', bold=True)
doc.add_paragraph()

styled_table(
    ['Module', 'Description', 'Access'],
    [
        ['User Management',        'Create, edit, activate/deactivate user accounts; bulk CSV import',   'IT Admin'],
        ['Activity Management',    'Full lifecycle: create, publish, archive, analyze feedback',          'CESO Staff'],
        ['Project Management',     'Strategic research initiatives and long-term program tracking',       'CESO Staff'],
        ['Announcements',          'Targeted communications to specific stakeholder groups',              'CESO Staff'],
        ['Website CMS',            'No-code editing of landing page content and sections',               'CESO Staff'],
        ['Dashboard',              'Role-specific charts, participation overview, AI insights',           'All Roles'],
        ['Feedback & Sentiment',   'Rating + comment submission; GPT-powered classification',             'All Roles'],
        ['Analytics',              'Effectiveness scores, sentiment reports, recommendations',            'CESO Staff'],
        ['Certificates',           'Auto-generated participation certificates with download',             'All Roles'],
        ['Profile Settings',       'Personal info, password change, data privacy consent',               'All Roles'],
    ],
    col_widths=[1.8, 3.2, 1.5]
)

# ════════════════════════════════════════════════════════════
# 2. USER ROLES SUMMARY
# ════════════════════════════════════════════════════════════
heading1('2. User Roles Summary')
body('The system enforces five distinct roles through Laravel middleware. Each role has a dedicated dashboard and access scope.')

styled_table(
    ['Role', 'Dashboard URL', 'Key Permissions'],
    [
        ['IT Administrator', '/it/dashboard',        'Full user management, system monitoring, all modules'],
        ['CESO Staff',       '/ceso/dashboard',      'Activities, projects, announcements, CMS, analytics, AI insights'],
        ['Student',          '/student/dashboard',   'Browse & join activities, submit feedback, certificates'],
        ['Faculty',          '/faculty/dashboard',   'Browse & join activities, submit feedback, certificates'],
        ['Staff (Inst.)',    '/staff/dashboard',     'Browse & join activities, submit feedback, certificates'],
        ['Community',        '/community/dashboard', 'Browse & join activities, submit feedback, certificates'],
    ],
    col_widths=[1.4, 1.8, 3.3]
)

note_box(
    'Community Users register through a public form at /community/register. '
    'All other roles are created by the IT Administrator.'
)

page_break()

# ════════════════════════════════════════════════════════════
# 3. GETTING STARTED
# ════════════════════════════════════════════════════════════
heading1('3. Getting Started')

heading2('3.1  Accessing the Portal')
body('Open any modern web browser (Chrome, Firefox, Edge, Safari) and navigate to:')
p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
r = p.add_run('https://www.ceso.me')
r.bold = True; r.font.size = Pt(12); r.font.color.rgb = MID_BLUE
doc.add_paragraph()
body('System requirements: stable internet connection, modern browser with JavaScript enabled.')

heading2('3.2  Community User Registration')
body('Community members self-register through the public registration form.')
numbered('Go to www.ceso.me and click Register as Community Member.')
numbered('Fill in the required fields:')
bullet('Full name (first name, last name)',        level=1)
bullet('Email address',                            level=1)
bullet('Contact number',                           level=1)
bullet('Address',                                  level=1)
bullet('Password (minimum 8 characters)',          level=1)
numbered('Read and accept the Data Privacy Consent notice.')
numbered('Click Submit Registration.')
numbered('Check your email for a verification link and click it to activate your account.')
note_box('IT Administrators, CESO Staff, Students, Faculty, and Institutional Staff accounts are created by the IT Administrator, not through self-registration.')

heading2('3.3  Login')
numbered('Go to www.ceso.me and click Login.')
numbered('Enter your registered email address and password.')
numbered('Click Sign In.')
numbered('You will be redirected to your role-specific dashboard automatically.')
warning_box('After 3 failed login attempts, wait 60 seconds before trying again. If you cannot remember your password, use Forgot Password.')

heading2('3.4  Email Verification')
body('New accounts require email verification before full access is granted.')
numbered('After registration, a verification email is sent to your inbox.')
numbered('Click the Verify Email Address button in the email.')
numbered('If you did not receive the email, log in and click Resend Verification Email.')
note_box('Check your spam or junk folder if the verification email does not appear in your inbox within 5 minutes.')

heading2('3.5  Forgot & Reset Password')
numbered('On the login page, click Forgot Password.')
numbered('Enter your registered email address and click Send Reset Link.')
numbered('Open the password reset email and click the reset link (valid for 60 minutes).')
numbered('Enter your new password twice and click Reset Password.')
numbered('Log in with your new password.')
warning_box('The reset link expires after 60 minutes. If expired, repeat the Forgot Password process to receive a new link.')

page_break()

# ════════════════════════════════════════════════════════════
# 4. IT ADMINISTRATOR GUIDE
# ════════════════════════════════════════════════════════════
heading1('4. IT Administrator Guide')
body('The IT Administrator has the highest system access and is responsible for user lifecycle management and system health monitoring.')

heading2('4.1  Dashboard')
body('The IT Dashboard provides a system-wide overview including:')
bullet('Total registered users by role')
bullet('Active vs. inactive account counts')
bullet('Recent user activity log')
bullet('System performance indicators')
numbered('Log in with your IT Administrator credentials.')
numbered('You are automatically redirected to /it/dashboard.')

heading2('4.2  User Management')
body('Navigate to Users in the left sidebar or go to /it/users.')

heading3('Creating a Single User')
numbered('Click Create New User.')
numbered('Fill in the user form:')
bullet('First Name, Last Name',     level=1)
bullet('Email address',             level=1)
bullet('Role (IT, CESO, Student, Faculty, Staff, Community)', level=1)
bullet('Password',                  level=1)
numbered('Click Save User. The user receives a welcome email with login instructions.')

heading3('Editing a User')
numbered('In the user list, click Edit next to the target user.')
numbered('Update the required fields.')
numbered('Click Update User to save changes.')

heading3('Activating / Deactivating a User')
numbered('In the user list, click Deactivate to suspend access without deleting the account.')
numbered('The user is immediately blocked from logging in.')
numbered('Click Activate to restore access at any time.')
note_box('Deactivated users retain all their data. Use Deactivate instead of Delete when you may need to restore the account later.')

heading3('Restoring a Deleted User')
numbered('Deleted users are soft-deleted and can be restored.')
numbered('Filter the user list to show archived/deleted users.')
numbered('Click Restore next to the user record.')

heading3('Permanently Deleting a User')
numbered('In the user list, click Delete next to the user.')
numbered('Confirm the deletion in the confirmation dialog.')
warning_box('Permanent deletion cannot be undone. All associated records are removed. Use Deactivate as a reversible alternative.')

heading2('4.3  Bulk CSV Import')
body('Import multiple users at once using a formatted CSV file.')
numbered('Go to Users → Import Users or click the Import button.')
numbered('Download the sample CSV template by clicking Download Sample CSV.')
numbered('Fill in the template with user data:')
bullet('Columns: first_name, last_name, email, role, password', level=1)
bullet('Role values: IT, CESO, Student, Faculty, Staff, Community', level=1)
bullet('Maximum 500 rows per import',                             level=1)
numbered('Upload the completed CSV file.')
numbered('Review the validation summary — errors are listed per row.')
numbered('Click Confirm Import to process valid rows.')
note_box('Rows with validation errors (duplicate email, invalid role) are skipped. Download the error report to correct and re-import failed rows.')

heading2('4.4  Profile Settings')
numbered('Click your name in the top navigation bar.')
numbered('Select Profile Settings.')
numbered('Update personal information and click Save Changes.')
numbered('To change password: enter current password, new password, confirm new password, and click Update Password.')

page_break()

# ════════════════════════════════════════════════════════════
# 5. CESO STAFF GUIDE
# ════════════════════════════════════════════════════════════
heading1('5. CESO Staff Guide')
body('CESO Staff manage the full community extension service lifecycle, from activity creation to AI-powered performance assessment.')

heading2('5.1  AI Dashboard')
body('The CESO Dashboard at /ceso/dashboard displays AI-generated insights powered by GPT-3.5-turbo alongside live statistics.')
bullet('Project statistics: total, active, completed, team members')
bullet('Activity statistics: total, upcoming, ongoing, completed, participants, average rating')
bullet('AI Insight Panel: summary, alerts, highlights, suggestions, next steps, and improvement areas')
body('The AI insight panel refreshes each time the dashboard loads. Insights are based on current project and activity data.')
note_box('AI insights are advisory only. They are generated from aggregate statistics and do not include personal user data.')

heading2('5.2  Activity Management')
body('Activities are the core unit of community extension services. Each activity has a unique 6-character entry code for participant verification.')

heading3('Creating an Activity')
numbered('Go to Activities → Create Activity or click the + New Activity button.')
numbered('Fill in the activity details:')
bullet('Title (required)',                                          level=1)
bullet('Venue',                                                     level=1)
bullet('Start Date and End Date',                                   level=1)
bullet('Start Time and End Time',                                   level=1)
bullet('Conducted By (person or department)',                       level=1)
bullet('Fee (0 for free activities)',                               level=1)
bullet('Description',                                               level=1)
numbered('Select participants by role:')
bullet('Faculty — select from registered faculty list',             level=1)
bullet('Staff — select from IT/CESO staff list',                    level=1)
bullet('Students — select from student list',                       level=1)
bullet('Community — select from community user list',               level=1)
bullet('Other — type names of participants not in the system',      level=1)
numbered('Click Create Activity. The system automatically generates a unique 6-character entry code.')
numbered('Share the entry code with invited participants to allow them to join.')
note_box('The entry code is displayed on the activity details page. Participants need this code to register their attendance.')

heading3('Editing an Activity')
numbered('Go to Activities and click the activity title.')
numbered('Click Edit Activity.')
numbered('Update the required fields.')
numbered('Click Update Activity.')

heading3('Archiving an Activity')
numbered('On the activity details page, click Archive Activity.')
numbered('Confirm the action. The activity moves to the Archived Activities list.')
numbered('Archived activities are read-only and removed from active views.')
numbered('To restore: go to Activities → Archived, find the activity, and click Restore.')

heading3('Viewing Activity Feedback & Sentiment Analysis')
numbered('Open the activity details page.')
numbered('Scroll to the Feedback section to view all submitted comments and ratings.')
numbered('The sentiment analysis runs automatically for each comment:')
bullet('Each comment is classified as Positive, Neutral, or Negative by GPT-4.1-mini', level=1)
bullet('The Effectiveness Score (0–100) is calculated from sentiment distribution',       level=1)
bullet('Overall performance rating: Excellent (≥80), Good (≥60), Fair (≥40), Needs Improvement (<40)', level=1)
numbered('Click Explain next to any feedback entry to view a one-sentence AI reasoning for that classification.')
numbered('Scroll to the Decision Support panel for:')
bullet('Key findings summary',           level=1)
bullet('Role-specific recommendations',  level=1)
bullet('Participant improvement areas',  level=1)

heading2('5.3  Project Management')
body('Projects represent strategic research initiatives and long-term programs managed by the CESO office.')

heading3('Creating a Project')
numbered('Go to Projects → Create Project.')
numbered('Fill in the project details:')
bullet('Title (required)',   level=1)
bullet('Description',        level=1)
bullet('Status',             level=1)
bullet('Team members',       level=1)
numbered('Click Create Project.')

heading3('Viewing & Editing a Project')
numbered('Go to Projects and click the project title to open details.')
numbered('Click Edit to modify project information.')
numbered('Click Update Project to save changes.')

heading3('Archiving a Project')
numbered('On the project details page, click Archive Project.')
numbered('To restore, go to Projects → Archived and click Restore.')

heading2('5.4  Announcement Management')
body('Announcements are broadcast to specific stakeholder groups and appear on recipient dashboards.')

heading3('Creating an Announcement')
numbered('Go to Announcements → Create Announcement.')
numbered('Enter the Title and Body content.')
numbered('Select the target audience (All Roles, Students, Faculty, Staff, Community, or specific combinations).')
numbered('Click Publish Announcement.')

heading3('Editing an Announcement')
numbered('In the announcements list, click the announcement title.')
numbered('Click Edit, update the content, and click Update.')

heading3('Archiving & Deleting Announcements')
numbered('To archive: click Archive. The announcement is hidden from recipients but preserved.')
numbered('To restore: go to the archived list and click Restore.')
numbered('To permanently delete: click Delete and confirm. This action cannot be undone.')

heading2('5.5  Website Content Management (CMS)')
body('The CMS allows CESO Staff to edit landing page content without coding knowledge.')
numbered('Go to Website CMS or navigate to /ceso/website.')
body('The following sections are editable:')

styled_table(
    ['Section', 'Editable Content'],
    [
        ['Hero',    'Main banner headline, subtitle, and call-to-action text'],
        ['About',   'About description, mission statement, vision statement'],
        ['News',    'Latest news/updates section content'],
        ['CTA',     'Call-to-action section text and button labels'],
        ['Contact', 'Office address, phone number, email, map embed'],
        ['Footer',  'Footer links, copyright text, social media links'],
    ],
    col_widths=[1.5, 5.0]
)

numbered('Click the section you want to edit.')
numbered('Update the fields in the edit form.')
numbered('Click Save. Changes are reflected on the public landing page immediately.')
warning_box('All content changes are live immediately. Preview your edits carefully before saving.')

heading2('5.6  Sentiment Analysis & Analytics')
body('The analytics system provides automated performance assessment based on participant feedback.')

heading3('Effectiveness Scoring Algorithm')
body('Each activity receives an effectiveness score calculated as:')
p = doc.add_paragraph()
p.paragraph_format.left_indent = Inches(0.5)
r = p.add_run('Score = (Positive × 100 + Neutral × 50 + Negative × 0) ÷ Total Feedback')
r.bold = True; r.font.size = Pt(10.5); r.font.color.rgb = DARK_BLUE
doc.add_paragraph()

styled_table(
    ['Score Range', 'Performance Rating', 'System Action'],
    [
        ['80 – 100', 'Excellent',          'Replication encouraged for future activities'],
        ['60 – 79',  'Good',               'Minor improvement suggestions generated'],
        ['40 – 59',  'Fair',               'Structural review recommended'],
        ['0 – 39',   'Needs Improvement',  'Immediate review and issue resolution triggered'],
    ],
    col_widths=[1.3, 1.8, 3.4]
)

heading3('Role-Specific Recommendations')
body('The system generates tailored improvement suggestions per participant type when low ratings are detected:')
bullet('Faculty: academic depth, Q&A time, pre-activity materials, CPE credits')
bullet('Students: interactivity, accessibility, practical skills, refreshments')
bullet('Institutional Staff: scheduling coordination, clear communication, incentives')
bullet('Community: simplified content, accessible venue, practical takeaways')

heading2('5.7  Profile Settings')
numbered('Click your name in the top navigation.')
numbered('Select Profile Settings.')
numbered('Update name, email, or other personal information and click Save.')
numbered('To change your password, enter your current password, new password, and confirmation, then click Update Password.')

page_break()

# ════════════════════════════════════════════════════════════
# 6. STUDENT GUIDE
# ════════════════════════════════════════════════════════════
heading1('6. Student Guide')
body('Students access the system through the Student Portal at /student/dashboard.')

heading2('6.1  Dashboard')
body('The student dashboard displays upcoming activities, recent announcements, and participation summary.')

heading2('6.2  Browsing Activities')
numbered('Click Activities in the sidebar or go to /student/activities.')
numbered('Browse the list of available activities with dates, venue, and description.')
numbered('Use the search bar to find activities by title, venue, or keyword.')

heading2('6.3  Joining an Activity')
body('Method 1 — Join from activity page:')
numbered('Click the activity title to open details.')
numbered('Click Join Activity.')
numbered('Enter the 6-character entry code provided by CESO Staff.')
numbered('Click Confirm. Your participation is recorded.')

body('Method 2 — Join with code directly:')
numbered('Click Join with Code in the sidebar.')
numbered('Enter the entry code and click Join.')

heading2('6.4  Submitting Feedback')
numbered('Go to My Activities or click the activity from your participation history.')
numbered('Click Submit Feedback.')
numbered('Select a star rating (1–5).')
numbered('Write your feedback comment (maximum 1,000 characters).')
numbered('Click Submit Feedback.')
note_box('You can submit feedback only once per activity. Feedback cannot be edited after submission.')

heading2('6.5  My Activities')
body('Go to My Activities to view all activities you have joined, your attendance status, and feedback submission history.')

heading2('6.6  Certificates')
body('After completing an activity, a participation certificate is automatically generated.')
numbered('Go to My Activities and find the completed activity.')
numbered('Click Download Certificate to save the PDF.')
numbered('Certificates include activity name, date, and your name.')

heading2('6.7  Profile Settings')
numbered('Click your name in the top navigation.')
numbered('Select Profile Settings.')
numbered('Update your personal information and click Save.')
numbered('To change your password, go to the Password tab and follow the prompts.')

page_break()

# ════════════════════════════════════════════════════════════
# 7. FACULTY GUIDE
# ════════════════════════════════════════════════════════════
heading1('7. Faculty Guide')
body('Faculty access the system through /faculty/dashboard. The Faculty Portal has the same core functionality as the Student Portal.')

heading2('7.1  Dashboard')
body('Displays activity invitations, upcoming events, participation summary, and announcements targeted at faculty.')

heading2('7.2  Browsing & Joining Activities')
numbered('Go to Activities → /faculty/activities.')
numbered('View activities you have been invited to or open public activities.')
numbered('Join using the entry code provided by CESO Staff.')
numbered('Alternatively, use Join with Code for direct code entry.')

heading2('7.3  Submitting Feedback')
body('Same process as the Student Guide Section 6.4. Navigate to My Activities, select the activity, and submit a rating and comment.')

heading2('7.4  My Activities & Certificates')
body('Access /faculty/my-activities to view participation history and download certificates for completed activities.')

heading2('7.5  Profile Settings')
body('Access profile settings via the top navigation. Update personal information and password under /faculty/profile.')

page_break()

# ════════════════════════════════════════════════════════════
# 8. INSTITUTIONAL STAFF GUIDE
# ════════════════════════════════════════════════════════════
heading1('8. Institutional Staff Guide')
body('Institutional Staff (non-CESO administrative staff) access the system through /staff/dashboard.')

heading2('8.1  Dashboard')
body('Displays activity invitations, announcements, and participation summary relevant to the Staff role.')

heading2('8.2  Browsing & Joining Activities')
numbered('Go to Activities → /staff/activities.')
numbered('Browse and join available activities using the entry code.')
numbered('Use Join with Code for direct code-based registration.')

heading2('8.3  Submitting Feedback & Certificates')
body('Same process as Student Guide Sections 6.4 and 6.6. Navigate to My Activities at /staff/my-activities.')

heading2('8.4  Profile Settings')
body('Access and update profile information via the top navigation under /staff/profile.')

page_break()

# ════════════════════════════════════════════════════════════
# 9. COMMUNITY USER GUIDE
# ════════════════════════════════════════════════════════════
heading1('9. Community User Guide')
body('Community Users are external participants who register through the public portal.')

heading2('9.1  Registration')
body('See Section 3.2 for the full registration process at www.ceso.me.')

heading2('9.2  Dashboard')
body('The community dashboard at /community/dashboard shows upcoming activities, recent announcements, and participation history.')

heading2('9.3  Browsing & Joining Activities')
numbered('Go to Activities → /community/activities.')
numbered('Click an activity to view full details (date, venue, description, fee).')
numbered('Click Join Activity and enter the 6-character entry code from the CESO office.')
numbered('Confirm your participation.')

heading2('9.4  Submitting Feedback')
body('Community users can submit feedback directly from the feedback form:')
numbered('Go to Feedback Form or navigate to /community/feedback.')
numbered('Select the activity from the dropdown.')
numbered('Enter your name/source and role.')
numbered('Select a star rating and write your comment.')
numbered('Click Submit Feedback.')
note_box('Each participant can submit feedback for a specific activity only once, verified by name/source.')

heading2('9.5  My Activities')
body('Access /community/my-activities to view all activities you have joined and your feedback history.')

heading2('9.6  Profile Settings')
body('Update personal information at /community/profile. Change your password through the password update form.')

page_break()

# ════════════════════════════════════════════════════════════
# 10. AI-POWERED FEATURES
# ════════════════════════════════════════════════════════════
heading1('10. AI-Powered Features')
body(
    'The CESO Portal integrates OpenAI GPT models through a dual-model architecture. '
    'All AI processing uses only the text content of feedback comments. '
    'No personal identifiers (names, emails, IDs) are transmitted to external AI services.'
)

heading2('10.1  Sentiment Classification (GPT-4.1-mini)')
body('Every feedback comment submitted is automatically classified into one of three categories:')

styled_table(
    ['Label', 'Meaning', 'Example'],
    [
        ['Positive', 'Feedback expresses satisfaction, praise, or strong approval',
         '"The workshop was extremely helpful and well-organized."'],
        ['Neutral',  'Feedback is balanced, mixed, or non-committal',
         '"It was okay. Some parts were good, others could improve."'],
        ['Negative', 'Feedback expresses dissatisfaction, criticism, or concern',
         '"Poor logistics and the content was not relevant to our work."'],
    ],
    col_widths=[1.0, 2.3, 3.2]
)

heading2('10.2  Sentiment Explanation (On-Demand)')
body('CESO Staff can request a one-sentence AI explanation for any individual feedback classification.')
numbered('On the activity details page, locate a feedback entry.')
numbered('Click the Explain button next to the sentiment label.')
numbered('A reasoning explanation appears below the comment (e.g., "Classified as Negative due to explicit criticism of organizational logistics and content relevance.").')

heading2('10.3  Dashboard Insight Synthesis (GPT-3.5-turbo)')
body('The CESO Dashboard generates six categories of AI insights from aggregate activity and project data:')
bullet('Summary — executive overview of current program performance')
bullet('Alerts — critical issues requiring immediate attention')
bullet('Highlights — achievements and positive developments to recognize')
bullet('Improvements — specific areas identified for enhancement')
bullet('Suggestions — actionable recommendations for program administrators')
bullet('Next Steps — prioritized immediate actions')
note_box(
    'Dashboard insights are generated fresh on each page load using current statistics. '
    'They are advisory outputs, not automated decisions. Human judgment should guide all actions.'
)

heading2('10.4  Data Privacy in AI Processing')
bullet('Only the comment text field is transmitted to OpenAI — no names, emails, or user IDs')
bullet('API transmission is encrypted via HTTPS')
bullet('Data handling aligns with the Philippine Data Privacy Act of 2012 (RA 10173)')
bullet('OpenAI processes data under its enterprise data processing terms')

page_break()

# ════════════════════════════════════════════════════════════
# 11. TROUBLESHOOTING
# ════════════════════════════════════════════════════════════
heading1('11. Troubleshooting')

heading2('Login Issues')
styled_table(
    ['Problem', 'Solution'],
    [
        ['Cannot log in — wrong credentials',
         'Use Forgot Password to reset. Check for typos in email address.'],
        ['Account says "inactive"',
         'Contact IT Administrator to reactivate your account.'],
        ['Redirected back to login after signing in',
         'Clear browser cookies and cache, then try again.'],
        ['Email not verified message',
         'Click Resend Verification Email and check your spam folder.'],
    ],
    col_widths=[2.5, 4.0]
)

heading2('Activity Issues')
styled_table(
    ['Problem', 'Solution'],
    [
        ['Entry code not accepted',
         'Verify the code with CESO Staff — codes are case-sensitive and exactly 6 characters.'],
        ['Activity not visible in list',
         'Confirm the activity is published. Check if you are logged into the correct role.'],
        ['Already joined error',
         'You have previously registered for this activity. Check My Activities.'],
        ['Cannot submit feedback',
         'Ensure all required fields (rating + comment) are filled. Only one submission per activity is allowed.'],
    ],
    col_widths=[2.5, 4.0]
)

heading2('System / Technical Issues')
styled_table(
    ['Problem', 'Solution'],
    [
        ['Page loads slowly',
         'Check internet connection speed. Close other browser tabs. Try during off-peak hours.'],
        ['Sentiment analysis not showing',
         'The AI service may be temporarily unavailable. Refresh the page or try again in a few minutes.'],
        ['Certificate not downloading',
         'Ensure pop-ups are allowed for this site. Try a different browser.'],
        ['CSV import failed',
         'Download the sample CSV template and verify column headers match exactly.'],
        ['Cannot edit website content',
         'Only CESO Staff accounts have CMS access. Verify your role with the IT Administrator.'],
    ],
    col_widths=[2.5, 4.0]
)

page_break()

# ════════════════════════════════════════════════════════════
# 12. FREQUENTLY ASKED QUESTIONS
# ════════════════════════════════════════════════════════════
heading1('12. Frequently Asked Questions')

faqs = [
    ('Who can register on their own?',
     'Only Community Users can self-register at /community/register. All other accounts (Student, Faculty, Staff, CESO, IT) must be created by the IT Administrator.'),
    ('Can I join an activity without an entry code?',
     'No. All activities require a 6-character entry code to register attendance. Contact the CESO office to obtain the code for your activity.'),
    ('Can I edit my feedback after submitting?',
     'No. Feedback is final once submitted. Each participant may submit one feedback entry per activity.'),
    ('How is the effectiveness score calculated?',
     'Score = (Positive responses × 100 + Neutral responses × 50) ÷ Total responses. A score of 80 or above is rated Excellent.'),
    ('Is my feedback data shared with anyone?',
     'Feedback text is processed by OpenAI GPT for sentiment classification. No personal identifiers are included. Aggregated results are visible to CESO Staff only.'),
    ('What happens to my data if my account is deactivated?',
     'Your data is retained and remains accessible to administrators. A deactivated account can be reactivated by the IT Administrator.'),
    ('Can I change my email address?',
     'Yes, through Profile Settings. After updating your email, a new verification email will be sent to the new address.'),
    ('How long does it take for AI sentiment analysis to process?',
     'Each feedback entry is analyzed within approximately 1.4 seconds. For activities with many responses, the full analysis may take a few seconds to load.'),
    ('Who do I contact for technical problems?',
     'Contact the IT Administrator for system issues. Contact CESO Staff for program or activity-related concerns.'),
    ('Can CESO Staff see my individual feedback?',
     'Yes. CESO Staff can view all feedback comments, ratings, and AI sentiment labels for activities they manage.'),
]

for q, a in faqs:
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(6)
    r1 = p.add_run('Q: ')
    r1.bold = True; r1.font.color.rgb = MID_BLUE; r1.font.size = Pt(10.5)
    r2 = p.add_run(q)
    r2.bold = True; r2.font.size = Pt(10.5); r2.font.color.rgb = TEXT_DARK
    p2 = doc.add_paragraph()
    p2.paragraph_format.left_indent = Inches(0.25)
    p2.paragraph_format.space_after = Pt(4)
    r3 = p2.add_run('A: ')
    r3.bold = True; r3.font.color.rgb = ACCENT; r3.font.size = Pt(10.5)
    r4 = p2.add_run(a)
    r4.font.size = Pt(10.5); r4.font.color.rgb = TEXT_DARK

page_break()

# ════════════════════════════════════════════════════════════
# 13. QUICK REFERENCE
# ════════════════════════════════════════════════════════════
heading1('13. Quick Reference')

heading2('Common Tasks by Role')
styled_table(
    ['Task', 'Role', 'Navigation Path'],
    [
        ['Create activity',           'CESO Staff',    'Activities → Create Activity'],
        ['View sentiment analysis',   'CESO Staff',    'Activities → [Activity Name]'],
        ['Edit landing page',         'CESO Staff',    'Website CMS → Edit Section'],
        ['Create announcement',       'CESO Staff',    'Announcements → Create'],
        ['Create project',            'CESO Staff',    'Projects → Create Project'],
        ['Import users (CSV)',        'IT Admin',       'Users → Import Users'],
        ['Deactivate user',           'IT Admin',       'Users → Edit → Deactivate'],
        ['Join activity',             'All Members',    'Activities → [Activity] → Join'],
        ['Join with entry code',      'All Members',    'Join with Code (sidebar)'],
        ['Submit feedback',           'All Members',    'My Activities → Submit Feedback'],
        ['Download certificate',      'All Members',    'My Activities → Download Certificate'],
        ['Reset password',            'All Users',      'Login Page → Forgot Password'],
        ['Update profile',            'All Users',      'Top Nav → Profile Settings'],
    ],
    col_widths=[2.2, 1.5, 2.8]
)

heading2('URL Reference')
styled_table(
    ['Page', 'URL'],
    [
        ['Public Landing Page',       'www.ceso.me'],
        ['Login',                     'www.ceso.me/login'],
        ['Community Registration',    'www.ceso.me/community/register'],
        ['Forgot Password',           'www.ceso.me/forgot-password'],
        ['IT Dashboard',              'www.ceso.me/it/dashboard'],
        ['CESO Dashboard',            'www.ceso.me/ceso/dashboard'],
        ['Student Dashboard',         'www.ceso.me/student/dashboard'],
        ['Faculty Dashboard',         'www.ceso.me/faculty/dashboard'],
        ['Staff Dashboard',           'www.ceso.me/staff/dashboard'],
        ['Community Dashboard',       'www.ceso.me/community/dashboard'],
        ['Public Announcements',      'www.ceso.me/announcements'],
        ['Community Feedback Form',   'www.ceso.me/community/feedback'],
    ],
    col_widths=[2.5, 4.0]
)

heading2('Support Contacts')
styled_table(
    ['Contact Type', 'Details'],
    [
        ['CESO Office',          '[ceso@institution.edu.ph]'],
        ['IT Support',           '[itsupport@institution.edu.ph]'],
        ['System Administrator', '[admin@institution.edu.ph]'],
        ['Portal URL',           'www.ceso.me'],
    ],
    col_widths=[2.5, 4.0]
)

# ── Footer note ──────────────────────────────────────────
doc.add_paragraph()
p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
r = p.add_run(
    'CESO Portal User Manual  •  Version 2.0  •  May 2026  •  '
    'Far Eastern University Institute of Technology / La Consolacion University'
)
r.font.size = Pt(8.5)
r.font.color.rgb = RGBColor(0x99, 0x99, 0x99)

# ── Save ─────────────────────────────────────────────────
out = r'c:\Users\ADMIN\Desktop\ceso\CESO_User_Manual.docx'
doc.save(out)
print(f'Saved: {out}')