- support env and do config more like a normal symfony app would, including environment overrides (example `.env.dev`,
  `.env.local`, etc)

final delivery:

- junie prompt based on the above information in markdown style
- do not use code fencing because there are issues with being able to copy and paste code snippets. instead, clearly
  mark the start and stop of prompts with `START PROMPT` and `END PROMPT`
- run necessary tooling to make sure the changes pass
- ensure that all documentation is consistent (especially the readme)

----

- support env and do config more like a normal symfony app would, including environment overrides (example `.env.dev`,
  `.env.local`, etc)

- we should split up the changelog command. we want to have an initialize command that will generate sectioned
  changelogs between any existing release tags. the behavior for our new/previous workflow is the desired behavior for
  the current changelog command but we just want to limit it to that. don't generate a prompt because we need to work
  out our use cases and workflow of using the new split commands.