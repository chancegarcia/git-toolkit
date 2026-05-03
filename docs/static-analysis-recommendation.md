# Static Analysis Recommendation

## Current Setup

The project currently uses **PHPStan** (Level 5) for static analysis. It is integrated into the Composer scripts (
`composer stan`) and the GitHub Actions CI workflow.

## Psalm Evaluation

### Pros of adding Psalm

- **Security Analysis**: Psalm has built-in taint analysis which can be useful for security-sensitive applications.
- **Different Engine**: Sometimes Psalm catches issues that PHPStan misses due to different implementation strategies.
- **Advanced Features**: Psalm offers features like `@psalm-immutable` and better support for some complex array shapes.

### Cons / Maintenance Cost

- **Redundancy**: Having two static analysis tools often leads to redundant checks and overlapping error reports.
- **Maintenance**: Requires keeping another configuration file (`psalm.xml`) and dependency up to date.
- **Learning Curve**: Developers need to understand how to suppress or fix issues in two different systems.
- **CI Time**: Adding Psalm will increase the total CI execution time.

## Comparison with PHPStan Strictness

PHPStan is already configured and working. Increasing the PHPStan level (e.g., from 5 to 6, 7, or 8) and adding strict
rulesets (like `phpstan/phpstan-strict-rules`) would provide significant improvements in code quality with much lower
overhead than introducing a second tool.

## Final Recommendation

**Defer adding Psalm.**

The project is currently at PHPStan Level 5. There is significant room for improvement within the existing toolset
before needing to add a secondary static analysis engine. Introducing Psalm now would add unnecessary maintenance
complexity.

## Suggested Next Steps

1. **Increase PHPStan Level**: Aim to reach Level 8 or 9 incrementally.
2. **Add Strict Rules**: Integrate `phpstan/phpstan-strict-rules` and `ergebnis/phpstan-rules` for even stricter checks.
3. **Bleeding Edge**: Enable PHPStan's `bleedingEdge` configuration to catch future-looking issues.
4. **Re-evaluate**: If the project grows significantly in complexity or handles high-risk security data, reconsider
   Psalm for its taint analysis.
