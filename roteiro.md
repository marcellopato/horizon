# Loom Script (<= 5 minutes) — Hireflix Clone

Goal: Record a concise, end-to-end demo showing sign in/sign up, creating an interview, candidate recording/uploading answers, and reviewer scoring. Keep a steady pace; total time under 5 minutes.

## 0) Setup (10–15s)
- Browser at: http://localhost (root redirects to /login)
- Test accounts ready:
  - Admin: admin@horizon.test / admin123
  - Reviewer: reviewer@horizon.test / reviewer123
  - Candidate: candidate@horizon.test / candidate123
- Note: No dashboard. After login, you go to Interviews.
- Ensure camera/mic permitted for the site (you’ll record very short clips ~2–3s).

Talk track: “This is a one-way video interview app built with Laravel. I’ll show login, creating an interview, candidate recording, and reviewer scoring — all in under five minutes.”

## 1) Sign in / Sign up (20–30s)
- Show /login. Point at the “Register” link (don’t register now to save time).
- Sign in as Reviewer: reviewer@horizon.test / reviewer123.

Talk track: “You can sign up here; Admin accounts are created via CLI for security. I’ll log in as a Reviewer.”

## 2) Create an Interview (60–75s)
- Click “Interviews” → “Create Interview”.
- Fill Title: “Frontend Engineer — Screening”, Description: “2 quick questions”.
- Add Question 1: “Introduce yourself” (Time limit: 30s).
- Add Question 2: “Why this role?” (Time limit: 30s).
- Save.

Talk track: “Reviewers create interviews with multiple questions and time limits. I’ll create a short, two-question interview.”

## 3) Candidate Recording & Submission (75–90s)
- Log out. Log in as Candidate: candidate@horizon.test / candidate123.
- Open the new interview → click “Start Interview”.
- Allow camera and microphone.
- For Q1: Record ~2–3 seconds → Stop → wait for upload progress to finish.
- For Q2: Record ~2–3 seconds → Stop → upload completes.
- Click “Submit Interview”.

Talk track: “Candidates record per question. The app handles permissions, timing, recording, and upload automatically, then submit the interview.”

## 4) Reviewer Scoring & Downloads (45–60s)
- Log out. Log back in as Reviewer.
- Go to “Submissions” → open the latest submission.
- Play an answer briefly; enter a score and a short comment for each.
- Add overall comments/score → Save/Mark as reviewed.
- (Optional) Click “Download all (ZIP)” and/or a per-answer download link.

Talk track: “Reviewers watch responses, leave scores and comments per answer, and can download the entire submission or individual videos.”

## 5) Close (10–15s)
- Briefly mention tests: “Full test suite is green (38 tests, 105 assertions).”
- Show the GitHub repo URL and the README for setup details.

Talk track: “That’s the full flow: create, record, review, and download. See the README for local setup, seeded accounts, and notes.”

## Tips (if something goes wrong)
- If camera permission fails, refresh the page and allow permissions.
- Keep each clip very short to save time on uploads.
- If the recording preview is black, switch camera in the browser site settings and retry.
