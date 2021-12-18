# Example Minimal

This is a minimal WordPress theme intended as a starting point for creating a real theme.
It was originally based on [Underscores](https://github.com/Automattic/_s) but has been simplified considerably.

To rename the theme to `Your Theme`, run the following command from within the `example-minimal` theme directory:

```
sed \
  -e 's/Example Minimal/Your Theme/g' \
  -e 's/Example_Minimal/Your_Theme/g' \
  -e 's/example_minimal/your_theme/g' \
  -e 's/example-minimal/your-theme/g' \
  -i *.css *.php *.txt inc/*.php js/*.js languages/*.pot template-parts/*.php
```
