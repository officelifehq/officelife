describe('Adminland - Basic account management', function () {
  it('should let me access adminland with the right permission level', function () {
    cy.login()

    cy.createCompany()

    cy.canAccess('/1/account', 100, 'Administration')
    cy.canAccess('/1/account', 200, 'Administration')
    cy.canNotAccess('/1/account', 300)
  })

  it('should let me access audit section only if admin', function () {
    cy.login()

    cy.createCompany()

    cy.canAccess('/1/account/audit', 100, 'Audit logs')
    cy.canNotAccess('/1/account/audit', 200)
    cy.canNotAccess('/1/account/audit', 300)
  })

  it('should let me access general settings section only if admin', function () {
    cy.login()

    cy.createCompany()

    cy.canAccess('/1/account/general', 100, 'General settings')
    cy.canNotAccess('/1/account/general', 200)
    cy.canNotAccess('/1/account/general', 300)
  })

  it('should let an administrator update the company name', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=general-admin-link]').click()

    cy.get('[data-cy=rename-company-button]').click()
    cy.get('[data-cy=cancel-rename-company-button]').click()
    cy.get('[data-cy=rename-company-button]').click()
    cy.get('[data-cy=company-name-input]').clear()
    cy.get('[data-cy=company-name-input]').type('Coca Cola')
    cy.get('[data-cy=submit-rename-company-button]').click()

    cy.get('[data-cy=company-name]').contains('Coca Cola')

    cy.hasAuditLog('Renamed the company from', '/1/account/general')

  })

  it('should let an administrator update the currency used in the company', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/account')
    cy.get('[data-cy=general-admin-link]').click()

    cy.get('[data-cy=update-currency-company-button]').click()
    cy.get('[data-cy=cancel-update-currency-company-button]').click()
    cy.get('[data-cy=update-currency-company-button]').click()
    cy.get('[data-cy=currency-selector]').select('EUR')
    cy.get('[data-cy=submit-update-currency-company-button]').click()

    cy.get('[data-cy=currency-used]').contains('EUR')

    cy.wait(400)

    cy.hasAuditLog('Changed the company’s currency from', '/1/account/general')

  })
})
