# OCD Websites

Custom WordPress themes for OCD clients. One folder per client. Each folder is a self-contained theme ready to drop into `/wp-content/themes/`.

## Repo layout

```
Websites/
├── README.md                          # this file
├── .gitignore
├── docs/
│   └── ocd-build-guidelines.md        # the build standard every theme follows
├── prompts/
│   └── new-client-build.md            # template prompt for kicking off a new build
└── <clientslug>/                      # one folder per client
    ├── style.css
    ├── functions.php
    ├── inc/
    ├── template-parts/
    ├── page-templates/
    ├── acf-json/
    ├── assets/
    └── README.md                      # per-client install handover doc
```

## Current clients

| Client | Folder | Status |
|---|---|---|
| Dent Electrical & Air | [`dentelectrical/`](./dentelectrical) | Built — handed to install agent |

## Build standards

Every theme in this repo follows the OCD WordPress Build Guidelines:

- ACF Pro with local JSON sync, Gravity Forms, vanilla CSS/JS only
- No page builders, no other form plugins, no CSS/JS frameworks, no build tooling
- One self-contained theme per client (no child themes, no shared base)
- Mandatory Thank You page at `/thank-you/` as the redirect target for every form
- Never strips default WordPress behaviour

Read [`docs/ocd-build-guidelines.md`](./docs/ocd-build-guidelines.md) before starting a new client.

## Starting a new client

1. Get the design from the client (Figma link, HTML file, or image mockup)
2. Pick a client slug (lowercase, no spaces — `dentelectrical`, `acmeplumbing`)
3. Open a Claude Code session in this repo with the prompt at `prompts/new-client-build.md`
4. Theme ships as `<clientslug>.zip` to the install agent

## Handing a theme to the install agent

Each client folder includes its own `README.md` with the install steps and Gravity Forms mandatory settings. Zip the folder and pass that file plus the install prompt to the install agent.
