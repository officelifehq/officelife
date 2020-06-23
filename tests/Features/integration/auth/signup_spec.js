describe('Signup', function () {
  before(function () {
    cy.exec('php artisan setup:frontendtesting')
  })

  it('should sign up and logout', function () {
    cy.visit('/signup')

    cy.get('input[name=email]').type('test@test.com')
    cy.get('input[name=password]').type('testtest')
    cy.get('[data-cy=accept-terms]').check()

    cy.get('button[type=submit]').click()

    cy.url().should('include', '/home')

    cy.contains('Create a company')

    cy.get('[data-cy=header-menu]').click()
    cy.get('[data-cy=logout-button]').click()

    cy.contains('Login')
  })

  it('should block registration if email is already taken', function () {
    cy.visit('/signup')

    cy.get('input[name=email]').type('test@test.com')
    cy.get('input[name=password]').type('testtest')
    cy.get('[data-cy=accept-terms]').check()

    cy.get('button[type=submit]').click()

    cy.contains('The email has already been taken.')
  })
})
