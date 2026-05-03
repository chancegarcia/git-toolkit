since this project has had low usage (less than 10 downloads in the entire history of the project) in the past and
because the newer features we're implementing are leaps and bounds better than we had before, we are considering making
this into a completely new project with new github repo and package name. While looking to see if anyone else has made a
similar project, we found a project that is doing what we want by default but with a lot more
complexity (https://github.com/marcocesarato/php-conventional-changelog). It has a lot of

final delivery:

- junie prompt based on the questions/statements. if it is a questions, answer the question and ask if we want to create
  a junie prompt.
- junie prompt is in markdown format.
- for the junie prompt: do not use code fencing because there are issues with being able to copy and paste code
  snippets. instead, clearly
  mark the start and stop of prompts with `START PROMPT` and `END PROMPT`
- instruct junie to run necessary tooling to make sure the changes pass
- instruct junie to ensure that all documentation is consistent (especially the readme)

----


- we should split up the changelog command. we want to have an initialize command that will generate sectioned
  changelogs between any existing release tags. the behavior for our new/previous workflow is the desired behavior for
  the current changelog command but we just want to limit it to that. don't generate a prompt because we need to work
  out our use cases and workflow of using the new split commands.