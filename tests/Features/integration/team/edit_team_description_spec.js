describe('Team - Edit description', function () {
  it('should let an administrator update the team description', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=add-description-blank-state]').click()

    cy.get('[data-cy=team-description-textarea]').type('I made a drawing')
    cy.get('[data-cy=team-description-submit-description-button]').click()

    cy.get('body').should('contain', 'I made a drawing')

    cy.hasAuditLog('Set the description of the team called', '/1/teams/1')
    cy.hasTeamLog('Set the team’s description', '/1/account/teams')
  })

  it('should let an HR update the team description', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)
    cy.changePermission(1, 200)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=add-description-blank-state]').click()

    cy.get('[data-cy=team-description-textarea]').type('I made a drawing')
    cy.get('[data-cy=team-description-submit-description-button]').click()

    cy.get('body').should('contain', 'I made a drawing')

    cy.hasTeamLog('Set the team’s description', '/1/account/teams')
  })

  it('should not let a random employee update the team description', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)
    cy.changePermission(1, 300)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=add-description-blank-state]').should('not.exist')
  })

  it('should let a team member update the team description', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.assignEmployeeToTeam(1, 1)

    cy.changePermission(1, 300)

    cy.visit('/1/teams/1')
    cy.get('[data-cy=add-description-blank-state]').click()

    cy.get('[data-cy=team-description-textarea]').type('I made a drawing')
    cy.get('[data-cy=team-description-submit-description-button]').click()

    cy.get('body').should('contain', 'I made a drawing')

    cy.changePermission(1, 100)
    cy.hasTeamLog('Set the team’s description', '/1/account/teams')
  })
})
