describe('Login', function () {
  beforeEach(function () {
    cy.exec('php artisan setup:frontendtesting')
  })

  it('should sign up and logout', function () {
    cy.visit('/login')

    cy.get('input[name=email]').type('admin@admin.com')
    cy.get('input[name=password]').type('admin')

    cy.get('button[type=submit]').click()

    cy.url().should('include', '/dashboard')
  })
})
