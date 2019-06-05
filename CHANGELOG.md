# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 0.1.0 - 2019-06-05

### Added

- Form Elements:
  - Currency as a text input
  - Currency as a select element
  - Amount as a Number element
  - A Money Fieldset that combines Currency and Amount elements that can be re-used in Forms.
- A Hydrator that will extract a Money instance to an array containing `currency` and `amount` keys and will hydrate
 an array in the same format to a Money instance. In each direction, the amount will be converted between an integer
 and a float and vice-versa
- An Input filter that can validate an array in the format expected by the hydrator
- A Currency validator that verifies currency code strings are sane and ensures they are permitted according to a
 pre-defined list of currencies.
- A ConfigProvider that wires up the form elements, hydrators, validators etc to the relevant plugin managers such as
 FormElementManager etc.
- A Default Currency concept that is simply a string at configuration top-level used by a factory to return it as a
 currency instance. The factory is aliased to `\Money\Currency`
- A Default List of allowable currencies - A factory that by default returns all ISO currencies as a `\Money\Currencies`
 instance. The factory is also aliased to this interface.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
