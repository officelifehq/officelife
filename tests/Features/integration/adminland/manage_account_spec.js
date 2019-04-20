describe('Adminland - Basic account management', function () {
  it('should let me access adminland with the right permission level', function () {
    cy.login()

    cy.createCompany()

    cy.wait(500)

    cy.canAccess('/1/account', 100, 'Administration')
    cy.canAccess('/1/account', 200, 'Administration')
    cy.canNotAccess('/1/account', 300)
  })

  it('should let me access audit section only if admin', function () {
    cy.login()

    cy.createCompany()

    cy.wait(500)

    cy.canAccess('/1/account/audit', 100, 'Audit logs')
    cy.canNotAccess('/1/account/audit', 200)
    cy.canNotAccess('/1/account/audit', 300)
  })
})
